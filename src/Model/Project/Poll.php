<?php

namespace App\Model\Project;

abstract class Poll
{
    /** @var Project **/
    protected $project;
    /** @var Details **/
    protected $details;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $endedAt;
    
    public function setProject(Project $project): Poll
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function setDetails(Details $details): Poll
    {
        $this->details = $details;
        
        return $this;
    }
    
    public function getDetails(): Details
    {
        return $this->details;
    }
    
    public function setCreatedAt(\DateTime $createdAt): Poll
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setEndedAt(\DateTime $endedAt): Poll
    {
        $this->endedAt = $endedAt;
        
        return $this;
    }
    
    public function getEndedAt(): \DateTime
    {
        return $this->endedAt;
    }
}