<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\Project\{
    Poll,
    Vote
};
use App\Entity\User\User;

class VoteManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getUserVote(Poll $poll, User $user): ?Vote
    {
        return $this->em->getRepository(Vote::class)->findOneBy([
            'poll' => $poll,
            'user' => $user
        ]);
    }
    
    public function vote(Poll $poll, User $user, bool $isPositive, string $choice): Vote
    {
        if ($this->getUserVote($poll, $user) !== null) {
            throw new BadRequestHttpException('projects.votes.already_voted');
        }
        if (!$this->supportChoice($choice)) {
            throw new BadRequestHttpException('projects.votes.invalid_option');
        }
        $vote =
            (new Vote())
            ->setPoll($poll)
            ->setUser($user)
            ->setIsPositive($isPositive)
            ->setChoice($choice)
        ;
        $this->em->persist($vote);
        $this->em->flush();
        return $vote;
    }
    
    protected function supportChoice(string $choice)
    {
        return in_array($choice, [
            Vote::CHOICE_NEGATIVE_INFOS,
            Vote::CHOICE_NEGATIVE_PURPOSE,
            Vote::CHOICE_NEGATIVE_TECH,
            Vote::CHOICE_POSITIVE_PURPOSE,
            Vote::CHOICE_POSITIVE_TECH
        ]);
    }
}