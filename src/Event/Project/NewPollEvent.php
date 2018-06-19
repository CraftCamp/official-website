<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\Poll;

class NewPollEvent extends Event
{
    /** @var Poll **/
    protected $poll;
    
    const NAME = 'project.new_poll';
    
    public function __construct(Poll $poll)
    {
        $this->poll = $poll;
    }
    
    public function getPoll(): Poll
    {
        return $this->poll;
    }
}