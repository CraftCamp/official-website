<?php

namespace App\Entity\Community;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Project\Project;

/**
 * @ORM\Entity()
 * @ORM\Table(name="communities__project")
 * @ORM\HasLifecycleCallbacks
 */
class CommunityProject
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
    
    public function setCommunity(Community $community): CommunityProject
    {
        $this->community = $community;
        
        return $this;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
    
    public function setProject(Project $project): CommunityProject
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
    
    public function setJoinedAt(\DateTime $joinedAt): CommunityProject
    {
        $this->joinedAt = $joinedAt;
        
        return $this;
    }
    
    public function getJoinedAt(): \DateTime
    {
        return $this->joinedAt;
    }
}