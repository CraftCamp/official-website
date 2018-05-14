<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\Project;
use App\Entity\User\User;

class NewMemberEvent extends Event
{
    /** @var Project **/
    protected $project;
    /** @var User **/
    protected $user;
    
    const NAME = 'project.new_member';
    
    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
}