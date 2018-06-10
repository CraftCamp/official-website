<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Project as ProjectModel;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\ProjectRepository")
 * @ORM\Table(name="project__projects")
 */
class Project extends ProjectModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(name="name", type="string", length=125, unique=true)
     */
    protected $name;
    /**
     * @ORM\Column(name="slug", type="string", length=125, unique=true)
     */
    protected $slug;
    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\ProductOwner")
     */
    protected $productOwner;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $organization;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\BetaTest", mappedBy="project")
     */
    protected $betaTests;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\Feature", mappedBy="project")
     */
    protected $features;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\Feedback", mappedBy="project")
     */
    protected $feedbacks;
	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Project\Repository", mappedBy="project")
	 */
	protected $repositories;

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

    public function setId(int $id): Project
    {
        $this->id = $id;

        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}