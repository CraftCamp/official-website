<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Repository as RepositoryModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__repositories__repository")
 * @ORM\HasLifecycleCallbacks
 */
abstract class Repository extends RepositoryModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Project")
     */
    protected $project;
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
    
    public function setId(int $id): Repository 
    {
        $this->id = $id;
        
        return this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}