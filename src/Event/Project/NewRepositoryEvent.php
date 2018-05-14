<?php

namespace App\Event\Project;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Project\{
    Project,
    Repository
};

class NewRepositoryEvent extends Event
{
    /** @var Project **/
    protected $project;
    /** @var Repository **/
    protected $repository;
    
    const NAME = 'project.new_repository';
    
    public function __construct(Project $project, Repository $repository)
    {
        $this->project = $project;
        $this->repository = $repository;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function getRepository(): Repository
    {
        return $this->repository;
    }
}