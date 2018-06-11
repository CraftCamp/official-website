<?php

namespace App\Model\Project;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class BetaTest
{
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $slug;
    /** @var Project **/
    protected $project;
    /** @var \DateTime **/
    protected $startedAt;
    /** @var \DateTime **/
    protected $endedAt;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    /** @var ArrayCollection **/
    protected $betaTesters;

    public function __construct()
    {
        $this->betaTesters = new ArrayCollection();
    }

    public function setName(string $name): BetaTest
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSlug(string $slug): BetaTest
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setProject(Project $project): BetaTest
    {
        $this->project = $project;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setStartedAt(\DateTime $startedAt): BetaTest
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    public function setEndedAt(\DateTime $endedAt): BetaTest
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getEndedAt(): \DateTime
    {
        return $this->endedAt;
    }

    public function setCreatedAt(\DateTime $createdAt): BetaTest
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): BetaTest
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function addBetaTester(UserInterface $betaTester): BetaTest
    {
        $this->betaTesters->add($betaTester);

        return $this;
    }

    public function removeBetaTester(UserInterface $betaTester): BetaTest
    {
        $this->betaTesters->removeElement($betaTester);

        return $this;
    }

    public function getBetaTesters(): ArrayCollection
    {
        return $this->betaTesters;
    }
}
