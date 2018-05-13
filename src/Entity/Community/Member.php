<?php

namespace App\Entity\Community;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="communities__member")
 */
class Member
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
    protected $user;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isLead;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $joinedAt;
    
    public function prePersist()
    {
        $this->joinedAt = new \DateTime();
    }
    
    public function setCommunity(Community $community): CommunityMember
    {
        $this->community = $community;
        
        return $this;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
    
    public function setUser(User $user): CommunityMember
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function setIsLead(bool $isLead): CommunityMember
    {
        $this->isLead = $isLead;
        
        return $this;
    }
    
    public function getIsLead(): bool
    {
        return $this->isLead;
    }
    
    public function setJoinedAt(\DateTime $joinedAt): CommunityMember
    {
        $this->joinedAt = $joinedAt;
        
        return $this;
    }
    
    public function getJoinedAt(): \DateTime
    {
        return $this->joinedAt;
    }
}