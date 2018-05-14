<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\Project;

class NewReleaseEvent extends Event
{
    /** @var Project **/
    protected $project;
    /** @var string **/
    protected $version;
    
    const NAME = 'project.new_release';
    
    public function __construct(Project $project, string $version)
    {
        $this->project = $project;
        $this->version = $version;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function getVersion(): string
    {
        return $this->version;
    }
}