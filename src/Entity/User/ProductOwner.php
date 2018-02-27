<?php

namespace App\Entity\User;

use App\Entity\Organization;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users__product_owner")
 */
class ProductOwner extends User {
    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $organization;

    /**
     * @param Organization $organization
     * @return Customer
     */
    public function setOrganization(Organization $organization) {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return Organization
     */
    public function getOrganization() {
        return $this->organization;
    }
}
