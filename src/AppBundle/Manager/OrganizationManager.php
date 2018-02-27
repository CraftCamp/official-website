<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;

use AppBundle\Utils\Slugger;

use AppBundle\Entity\Organization;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @param array $data
	 * @return Organization
     */
    public function createOrganization($data): Organization {
		if (empty($data['name'])) {
			throw new BadRequestHttpException('organizations.invalid_name');
		}
		if (empty($data['type']) || !in_array($data['type'], Organization::getTypes())) {
			throw new BadRequestHttpException('organizations.invalid_type');
		}
		if (!isset($data['description'])) {
			throw new BadRequestHttpException('organization.invalid_description');
		}
		$organization =
			(new Organization())
			->setName($data['name'])
			->setSlug($this->slugger->slugify($data['name']))
            ->setType($data['type'])
			->setDescription($data['description'])
		;
        $this->em->persist($organization);
        $this->em->flush();
		
		return $organization;
    }
}
