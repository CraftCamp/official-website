<?php

namespace App\Model\Project;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Job {
    /** @var string */
    protected $name;
    /** @var string */
    protected $slug;
    /** @var string*/
    protected $description;
    /** @var \DateTime*/
    protected $createdAt;
    /** @var \DateTime */
    protected $updatedAt;
    /** @var int */
    protected $status;
    /** @var Project */
    protected $project;
    /** @var UserInterface */
    protected $responsible;

    const TYPE_FEATURE = 'US';
    const TYPE_FEEDBACK = 'FB';

    public function setName(string $name): Job
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSlug(string $slug): Job
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setDescription(string $description): Job
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setProject(Project $project): Job
    {
        $this->project = $project;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setStatus(int $status): Job
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setResponsible(UserInterface $responsible = null): Job
    {
        $this->responsible = $responsible;

        return $this;
    }

    public function getResponsible(): UserInterface
    {
        return $this->responsible;
    }

    public function setCreatedAt(\DateTime $createdAt): Job
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): Job
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
