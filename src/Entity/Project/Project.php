<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Organization;

use Developtech\AgilityBundle\Entity\Project as ProjectModel;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\ProjectRepository")
 * @ORM\Table(name="developtech_agility__projects")
 */
class Project extends ProjectModel implements \JsonSerializable
{
    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $organization;

    /**
     * @param Organization $organization
     * @return Customer
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
    
    public function jsonSerialize()
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