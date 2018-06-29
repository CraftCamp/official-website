<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Vote as VoteModel;

/**
 * @ORM\Table(name="project__votes")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Vote extends VoteModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Poll")
     */
    protected $poll;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $user;
    /**
     * @ORM\Column(type="string")
     */
    protected $choice;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPositive;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function setId(int $id): Vote
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}