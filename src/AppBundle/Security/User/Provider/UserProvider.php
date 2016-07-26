<?php

namespace AppBundle\Security\User\Provider;

use Symfony\Component\Security\Core\User\{UserProviderInterface, UserInterface};
use Symfony\Component\Security\Core\Exception\{UsernameNotFoundException, UnsupportedUserException};

use AppBundle\Manager\UserManager;

use AppBundle\Entity\User\User;

class UserProvider implements UserProviderInterface {
    /** @var \AppBundle\Manager\UserManager **/
    protected $manager;

    /**
     * @param \AppBundle\Manager\UserManager $manager
     */
    public function __construct(UserManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param string $username
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function loadUserByUsername($username): UserInterface {
        if(($user = $this->manager->findUserByUsernameOrEmail($username)) === null) {
            throw new UsernameNotFoundException("Username \"$username\" does not exist.");
        }
        return $user;
    }

    /**
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \Symfony\Component\Security\Core\User\UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user): UserInterface {
        if (!$user instanceof User) {
            throw new UnsupportedUserException('Instances of ' . get_class($user) . ' are not supported.');
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class) {
        return is_a($class, User::class, true);
    }
}
