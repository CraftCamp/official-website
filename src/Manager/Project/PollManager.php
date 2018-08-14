<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Event\Project\{
    AcceptedPollEvent,
    NewPollEvent,
    RejectedPollEvent
};

use App\Entity\Project\{
    Details,
    Poll,
    Project
};

use App\Gateway\SchedulerGateway;

class PollManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    /** @var SchedulerGateway **/
    protected $scheduler;
    /** @var string **/
    protected $pollDuration;
    
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SchedulerGateway $scheduler, string $pollDuration)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->scheduler = $scheduler;
        $this->pollDuration = $pollDuration;
    }
    
    public function create(Project $project, Details $details): Poll
    {
        $poll =
            (new Poll())
            ->setProject($project)
            ->setDetails($details)
            ->setCreatedAt(new \DateTime())
            ->setEndedAt(new \DateTime($this->pollDuration))
        ;
        $project->setApprovalPoll($poll);
        $this->em->persist($poll);
        $this->em->flush();
        $this->scheduler->addTask(
            'PUT',
            "http://craftcamp_website/projects/{$poll->getProject()->getSlug()}/polls/{$poll->getId()}/close",
            "poll-{$poll->getId()}-close",
            $poll->getEndedAt()
        );
        $this->eventDispatcher->dispatch(NewPollEvent::NAME, new NewPollEvent($poll));
        return $poll;
    }
    
    public function get(int $id): ?Poll
    {
        return $this->em->getRepository(Poll::class)->find($id);
    }
 
    public function getCurrentProjectPoll(Project $project): ?Poll
    {
        $results = $this->em->getRepository(Poll::class)->findBy([
            'project' => $project
        ], [
            'createdAt' => 'DESC'
        ], 1);
        if (count($results) === 0 || $results[0]->getEndedAt() < new \DateTime()) {
            return null;
        }
        return $results[0];
    }
    
    public function processResults(Poll $poll, array $votes)
    {
        $count = 0;
        $nbVotes = count($votes);
        foreach ($votes as $vote) {
            if ($vote->getIsPositive()) {
                $count++;
            }
        }
        $score = ($nbVotes > 0) ? round($count * 100 / $nbVotes, 2) : 0;
        
        $poll->setIsEnded(true);
        
        if ($score > 50) {
            $poll->getProject()->setApprovalPoll($poll);

            $this->eventDispatcher->dispatch(AcceptedPollEvent::NAME, new AcceptedPollEvent($poll, $score, $nbVotes));
        } else {
            $this->eventDispatcher->dispatch(RejectedPollEvent::NAME, new RejectedPollEvent($poll, $score, $nbVotes));
        }
        $this->em->flush();
    }
}