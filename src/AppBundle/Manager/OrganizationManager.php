<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;

use AppBundle\Utils\Slugger;

use AppBundle\Entity\Organization;

class OrganizationManager {
    /** @var Doctrine\ORM\EntityManager **/
    protected $em;
    /** @var AppBundle\Utils\Slugger **/
    protected $slugger;

    /**
     * @param EntityManager $em
     * @param Slugger $slugger
     */
    public function __construct(EntityManager $em, Slugger $slugger) {
        $this->em = $em;
        $this->slugger = $slugger;
    }

    /**
     * @param Organization $organization
     */
    public function createOrganization(Organization $organization) {
        $organization->setSlug($this->slugger->slugify($organization->getName()));

        $this->em->persist($organization);
        $this->em->flush();
    }
}
