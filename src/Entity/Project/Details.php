<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Details as DetailsModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__details")
 * @ORM\HasLifecycleCallbacks
 */
class Details extends DetailsModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="details")
     * @ORM\JoinColumn(onDelete="CASCADE")
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
    
    public function setId(int $id): Details
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}