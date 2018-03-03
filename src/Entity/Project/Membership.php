<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use Developtech\AgilityBundle\Entity\Project;

use App\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__members")
 * @ORM\HasLifecycleCallbacks
 */
class Membership implements \JsonSerializable
{
    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\Id
     **/
    protected $user;
    /**
     * @var Project
     * 
     * @ORM\ManyToOne(targetEntity="Developtech\AgilityBundle\Entity\Project")
     * @ORM\Id
     **/
    protected $project;
    /**
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     **/
    protected $isActive;
    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     **/
    protected $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime") 
     **/
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
    
    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param Project $project
     * @return $this
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
        
        return $this;
    }
    
    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }
    
    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function jsonSerialize()
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