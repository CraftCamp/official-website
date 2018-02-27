<?php

namespace App\DataFixtures\ORM\User;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use Symfony\Component\DependencyInjection\{
    ContainerInterface,
    ContainerAwareInterface
};

use App\Entity\User\ActivationLink;

class LoadActivationLinkData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
    /** @var ContainerInterface */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $data = include(dirname(__DIR__) . '/fixtures/activation_links.php');

        foreach($data as $activationLinkData) {
            $activationLink =
                (new ActivationLink())
                ->setId($activationLinkData['id'])
                ->setHash($activationLinkData['hash'])
                ->setCreatedAt(new \DateTime($activationLinkData['created_at']))
            ;
            $manager->persist($activationLink);
            $this->addReference("activation-link-{$activationLink->getId()}", $activationLink);
        }
        $manager->flush();
        $manager->clear(ActivationLink::class);
    }

    public function getOrder() {
        return 2;
    }
}
