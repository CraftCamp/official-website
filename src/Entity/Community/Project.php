<?php

namespace App\Entity\Community;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Project\Project as ProjectModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="communities__project")
 * @ORM\HasLifecycleCallbacks
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Community\Community")
     */
    protected $community;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $project;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $joinedAt;
    
    public function setCommunity(Community $community): Project
    {
        $this->community = $community;
        
        return $this;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
    
    public function setProject(ProjectModel $project): Project
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function getProject(): ProjectModel
    {
        return $this->project;
    }
    
    public function setJoinedAt(\DateTime $joinedAt): Project
    {
        $this->joinedAt = $joinedAt;
        
        return $this;
    }
    
    public function getJoinedAt(): \DateTime
    {
        return $this->joinedAt;
    }
}