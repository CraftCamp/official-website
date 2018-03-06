<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Organization;

use Developtech\AgilityBundle\Entity\Project as ProjectModel;

/**
 * @ORM\Entity()
 * @ORM\Table(name="developtech_agility__projects")
 */
class Project extends ProjectModel
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
}