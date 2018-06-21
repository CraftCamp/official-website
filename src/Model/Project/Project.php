<?php

namespace App\Model\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Model\Organization;

abstract class Project implements \JsonSerializable
{
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $slug;
    /** @var string **/
    protected $description;
    /** @var Poll **/
    protected $approvalPoll;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    /** @var ArrayCollection **/
    protected $betaTests;
    /** @var UserInterface **/
    protected $productOwner;
    /** @var Organization **/
    protected $organization;
    /** @var ArrayCollection **/
    protected $features;
    /** @var ArrayCollection **/
    protected $feedbacks;
	/** @var ArrayCollection **/
	protected $repositories;

    public function __construct()
    {
        $this->betaTests = new ArrayCollection();
        $this->features = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
        $this->repositories = new ArrayCollection();
    }

    public function setName(string $name): Project
    {
        $this->name = $name;

        return $this;
    }

        public function getName(): string
    {
        return $this->name;
    }

    public function setSlug(string $slug): Project
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setDescription($description): Project
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setApprovalPoll(Poll $poll): Project
    {
        $this->approvalPoll = $poll;
        
        return $this;
    }
    
    public function getApprovalPoll(): ?Poll
    {
        return $this->approvalPoll;
    }

    public function setCreatedAt($createdAt): Project
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt($updatedAt): Project
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function addBetaTest(BetaTest $betaTest): Project
    {
        $this->betaTests->add($betaTest);

        return $this;
    }

    public function removeBetaTest(BetaTest $betaTest): Project
    {
        $this->betaTests->removeElement($betaTest);

        return $this;
    }

    public function hasBetaTest(BetaTest $betaTest): bool
    {
        return $this->betaTests->contains($betaTest);
    }

    public function getBetaTests(): ArrayCollection
    {
        return $this->betaTests;
    }

    public function setProductOwner(UserInterface $productOwner): Project
    {
        $this->productOwner = $productOwner;

        return $this;
    }

    public function getProductOwner(): UserInterface
    {
        return $this->productOwner;
    }

    public function setOrganization(Organization $organization): Project
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function addFeature(Job $feature): Project
    {
        $this->features->add($feature);

        return $this;
    }

    public function removeFeature(Job $feature): Project
    {
        $this->features->removeElement($feature);

        return $this;
    }

    public function hasFeature(Job $feature): bool
    {
        return $this->features->contains($feature);
    }

    public function getFeatures(): ArrayCollection
    {
        return $this->features;
    }

    public function addFeedback(Job $feedback): Project
    {
        $this->feedbacks->add($feedback);

        return $this;
    }

    public function removeFeedback(Job $feedback): Project
    {
        $this->feedbacks->removeElement($feedback);

        return $this;
    }

    public function hasFeedback(Job $feedback): bool
    {
        return $this->feedbacks->contains($feedback);
    }

    public function getFeedbacks(): ArrayCollection
    {
        return $this->feedbacks;
    }

    public function addRepository(Repository $repository): Project
    {
        $this->repositories->add($repository);

        return $this;
    }

    public function removeRepository(Repository $repository): Project
    {
        $this->repositories->removeElement($repository);

        return $this;
    }

    public function hasRepository(Repository $repository): bool
    {
        return $this->repositories->contains($repository);
    }

    public function getRepositories(): ArrayCollection
    {
        return $this->repositories;
    }
    
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'product_owner' => $this->productOwner,
            'features' => $this->features,
            'feedbacks' => $this->feedbacks,
            'repositories' => $this->repositories,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}
