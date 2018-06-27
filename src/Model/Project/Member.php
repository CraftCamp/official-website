<?php

namespace App\Model\Project;

use App\Model\User\User;

abstract class Member implements \JsonSerializable
{
    /** @var User **/
    protected $user;
    /** @var Project **/
    protected $project;
    /** @var bool **/
    protected $isActive;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    
    public function setUser(User $user): Member
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function setProject(Project $project): Member
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function setIsActive(bool $isActive): Member
    {
        $this->isActive = $isActive;
        
        return $this;
    }
    
    public function getIsActive(): bool
    {
        return $this->isActive;
    }
    
    public function setCreatedAt(\DateTime $createdAt): Member
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setUpdatedAt(\DateTime $updatedAt): Member
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
            'user' => $this->user,
            'project' => $this->project,
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}