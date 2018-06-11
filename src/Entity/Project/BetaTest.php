<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\BetaTest as BetaTestModel;

/**
 * @ORM\Entity(repositoryClass="DevelopTech\AgilityBundle\Repository\BetaTestRepository")
 * @ORM\Table(name="project__beta_tests")
 * @ORM\HasLIfecycleCallbacks()
 */
class BetaTest extends BetaTestModel
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Project", inversedBy="betaTests")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $project;
    /**
     * @ORM\Column(name="started_at", type="datetime")
     */
    protected $startedAt;
    /**
     * @ORM\Column(name="ended_at", type="datetime")
     */
    protected $endedAt;
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User\BetaTester")
     * @ORM\JoinTable(name="project__beta_testers")
     */
    protected $betaTesters;

    public function setId(int $id): BetaTest
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

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
