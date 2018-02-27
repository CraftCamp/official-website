<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use Symfony\Component\DependencyInjection\{
    ContainerInterface,
    ContainerAwareInterface
};
use App\Entity\Organization;

class LoadOrganizationData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = include('fixtures/organizations.php');
        foreach ($data as $organizationData)
        {
            $organization =
                (new Organization())
                ->setId($organizationData['id'])
                ->setName($organizationData['name'])
                ->setType($organizationData['type'])
                ->setSlug($organizationData['slug'])
                ->setDescription($organizationData['description'])
                ->setCreatedAt(new \DateTime($organizationData['created_at']))
                ->setUpdatedAt(new \DateTime($organizationData['updated_at']))
            ;
            $manager->persist($organization);
            $this->addReference("organization-{$organization->getId()}", $organization);
        }
        $manager->flush();
        $manager->clear(Organization::class);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
