<?php

namespace App\Model\Project;

abstract class Details implements \JsonSerializable
{
    /** @var Project **/
    protected $project;
    /** @var string **/
    protected $needDescription;
    /** @var string **/
    protected $targetDescription;
    /** @var string **/
    protected $goalDescription;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    
    public function setProject(Project $project): Details
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function setNeedDescription(string $description): Details
    {
        $this->needDescription = $description;
        
        return $this;
    }
    
    public function getNeedDescription(): string
    {
        return $this->needDescription;
    }
    
    public function setTargetDescription(string $description): Details
    {
        $this->targetDescription = $description;
        
        return $this;
    }
    
    public function getTargetDescription(): string
    {
        return $this->targetDescription;
    }
    
    public function setGoalDescription(string $description): Details
    {
        $this->goalDescription = $description;
        
        return $this;
    }
    
    public function getGoalDescription(): string
    {
        return $this->goalDescription;
    }
    
    public function setCreatedAt(\DateTime $createdAt): Details
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setUpdatedAt(\DateTime $updatedAt): Details
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
    
    public function jsonSerialize(): array
    {
        return [
            'project' => $this->project,
            'need_description' => $this->needDescription,
            'target_description' => $this->targetDescription,
            'goal_description' => $this->goalDescription,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}