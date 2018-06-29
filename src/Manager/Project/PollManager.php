<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Event\Project\NewPollEvent;

use App\Entity\Project\{
    Details,
    Poll,
    Project
};

class PollManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    /** @var string **/
    protected $pollDuration;
    
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, string $pollDuration)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->pollDuration = $pollDuration;
    }
    
    public function createPoll(Project $project, Details $details): Poll
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
}