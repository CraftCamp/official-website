<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\Project;

class NewProjectEvent extends Event
{
    /** @var Project **/
    protected $project;
    
    const NAME = 'project.new_project';
    
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
}