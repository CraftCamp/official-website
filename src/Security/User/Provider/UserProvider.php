<?php

namespace App\Security\User\Provider;

use Symfony\Component\Security\Core\User\{UserProviderInterface, UserInterface};
use Symfony\Component\Security\Core\Exception\{UsernameNotFoundException, UnsupportedUserException};

use App\Manager\UserManager;

use App\Entity\User\User;

class UserProvider implements UserProviderInterface
{
    /** @var UserManager **/
    protected $manager;

    /**
     * @param UserManager $manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param string $username
     * @throws UsernameNotFoundException
     * @return UserInterface
     */
    public function loadUserByUsername($username): UserInterface
    {
        if(($user = $this->manager->findUserByUsernameOrEmail($username)) === null) {
            throw new UsernameNotFoundException("Username \"$username\" does not exist.");
        }
        return $user;
    }

    /**
     *
     * @param UserInterface $user
     * @return UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException('Instances of ' . get_class($user) . ' are not supported.');
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return is_a($class, User::class, true);
    }
}
