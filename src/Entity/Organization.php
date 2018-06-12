<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Organization as OrganizationModel;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 */
class Organization extends OrganizationModel
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=125)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=125)
     */
    protected $slug;
    /**
     * @ORM\Column(type="string", length=25) 
     */
    protected $type;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
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

    public function setId($id): Organization
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
