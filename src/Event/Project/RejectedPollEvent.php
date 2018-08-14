<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\Poll;

class RejectedPollEvent extends Event
{
    /** @var Poll **/
    protected $poll;
    /** @var float **/
    protected $score;
    /** @var int **/
    protected $nbVotes;
    
    const NAME = 'project.rejected_poll';
    
    public function __construct(Poll $poll, float $score, int $nbVotes)
    {
        $this->poll = $poll;
        $this->score = $score;
        $this->nbVotes = $nbVotes;
    }
    
    public function getPoll(): Poll
    {
        return $this->poll;
    }
    
    public function getScore(): float
    {
        return $this->score;
    }
    
    public function getNbVotes(): int
    {
        return $this->nbVotes;
    }
}