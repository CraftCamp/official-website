<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Member as MemberModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__members")
 * @ORM\HasLifecycleCallbacks
 */
class Member extends MemberModel
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\Id
     **/
    protected $user;
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\Id
     **/
    protected $project;
    /**
     * @ORM\Column(type="boolean")
     **/
    protected $isActive;
    /**
     * @ORM\Column(type="datetime")
     **/
    protected $createdAt;
    /**
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
}