<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__details")
 * @ORM\HasLifecycleCallbacks
 */
class Details
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="App\Entity\Project\Project")
     */
    protected $project;
    /**
     * @ORM\Column(type="text")
     */
    protected $needDescription;
    /**
     * @ORM\Column(type="text")
     */
    protected $targetDescription;
    /**
     * @ORM\Column(type="text")
     */
    protected $goalDescription;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
    
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
}