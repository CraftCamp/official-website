<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\{
    AbstractFixture,
    OrderedFixtureInterface
};
use Symfony\Component\DependencyInjection\{
    ContainerInterface,
    ContainerAwareInterface
};
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
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
        $data = include('fixtures/users.php');

        foreach($data as $userData) {
            $user =
                (new User())
                ->setId((int) $userData['id'])
                ->setUsername($userData['username'])
                ->setEmail($userData['email'])
                ->setPlainPassword($userData['plain_password'])
                ->setSalt(md5(uniqid(null, true)))
                ->enable($userData['is_active'])
                ->setIsLocked($userData['is_locked'])
                ->setCreatedAt(new \DateTime($userData['created_at']))
                ->setUpdatedAt(new \DateTime($userData['updated_at']))
            ;
            foreach($userData['roles'] as $role) {
                $user->addRole($role);
            }
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $userData['plain_password']);
            $user->setPassword($password);

            $manager->persist($user);
            $this->addReference("user-{$user->getId()}", $user);
        }
        $manager->flush();
        $manager->clear(User::class);
    }

    /**
     * @return int
     */
    public function getOrder() {
        return 1;
    }
}
