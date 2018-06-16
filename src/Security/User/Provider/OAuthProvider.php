<?php

namespace App\Security\User\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use App\Entity\User\User;
use App\Entity\User\Member;
use App\Manager\UserManager;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use App\Utils\Slugger;

class OAuthProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /** @var UserManager **/
    protected $userManager;
    /** @var Slugger **/
    protected $slugger;
    
    public function __construct(UserManager $userManager, Slugger $slugger)
    {
        $this->userManager = $userManager;
        $this->slugger = $slugger;
    }
    
    public function loadUserByUsername($username)
    {
        return $this->userManager->findUserByUsernameOrEmail($username);
    }
    
    public function loadUserByServiceId(string $service, int $id)
    {
        return $this->userManager->findUserByServiceId($service, $id);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return is_a($class, User::class, true);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface
    {
        $service = ucfirst($response->getResourceOwner()->getName());
        $serviceId = $response->getData()['id'];
        if (($user = $this->loadUserByServiceId($service, $serviceId)) !== null) {
            if (($id = $user->{"get{$service}Id"}()) !== $serviceId) {
                throw new AuthenticationException('users.connection.account_already_bound');
            }
            return $user;
        }
        $user =
            (new Member())
            ->setUsername(
                ($this->userManager->checkUsername($response->getRealName()))
                ? $response->getRealName()
                : $response->getEmail()
            )
            ->setEmail($response->getEmail())
            ->{"set{$service}Id"}($serviceId)
            ->{"set{$service}AccessToken"}($response->getAccessToken())
        ;
//        if ($response->getProfilePicture() !== null) {
//            $this->setUserProfilePicture($user, $this->slugger->slugify($username), $response->getProfilePicture());
//        }
        $this->userManager->createOAuthUser($user);
        return $user;
    }
    
//    protected function setUserProfilePicture(User $user, string $pictureName, string $profilePicture)
//    {
//        $extension = pathinfo($profilePicture, PATHINFO_EXTENSION);
//        $path = "{$pictureName}.{$extension}";
//        file_put_contents(Avatar::UPLOAD_DIR . "/{$path}", file_get_contents($profilePicture));
//        $user->setAvatar(
//            (new Avatar())
//            ->setName($pictureName)
//            ->setPath($path)
//        );
//    }
}