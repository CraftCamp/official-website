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
use App\Entity\User\ProductOwner;

class LoadProductOwnerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        $data = include(dirname(__DIR__) . '/fixtures/product_owners.php');

        foreach($data as $productOwnerData) {
            $productOwner =
                (new ProductOwner())
                ->setId((int) $productOwnerData['id'])
                ->setUsername($productOwnerData['username'])
                ->setEmail($productOwnerData['email'])
                ->setPlainPassword($productOwnerData['plain_password'])
                ->setSalt(md5(uniqid(null, true)))
                ->enable($productOwnerData['is_enabled'])
                ->setIsLocked($productOwnerData['is_locked'])
                ->addOrganization($this->getReference("organization-{$productOwnerData['organization_id']}"))
                ->setCreatedAt(new \DateTime($productOwnerData['created_at']))
                ->setUpdatedAt(new \DateTime($productOwnerData['updated_at']))
            ;
            if ($this->hasReference("activation-link-{$productOwnerData['activation_link_id']}")) {
                $productOwner->setActivationLink($this->getReference("activation-link-{$productOwnerData['activation_link_id']}"));
            }
            foreach($productOwnerData['roles'] as $role) {
                $productOwner->addRole($role);
            }
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($productOwner, $productOwnerData['plain_password']);
            $productOwner->setPassword($password);

            $manager->persist($productOwner);
            $this->addReference("product-owner-{$productOwner->getId()}", $productOwner);
        }
        $manager->flush();
        $manager->clear(ProductOwner::class);
    }

    /**
     * @return int
     */
    public function getOrder() {
        return 3;
    }
}
