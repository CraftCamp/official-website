<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\Project;
use App\Entity\Community\Community;

class NewCommunityEvent extends Event
{
    /** @var Project **/
    protected $project;
    /** @var Community **/
    protected $community;
    
    const NAME = 'project.new_community';
    
    public function __construct(Project $project, Community $community)
    {
        $this->project = $project;
        $this->community = $community;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
}