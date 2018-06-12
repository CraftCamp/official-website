<?php

namespace App\Security\User\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use App\Entity\Avatar;
use App\Entity\User\User;
use App\Entity\User\Member;
use App\Manager\UserManager;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GoogleResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\FacebookResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GitHubResourceOwner;
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
        $service = $this->getOAuthService($response->getResourceOwner());
        if (($user = $this->loadUserByUsername($response->getEmail())) !== null) {
            if (($id = $user->{"get{$service}Id"}()) !== $response->getData()['id']) {
                throw new AuthenticationException('users.connection.unbound_accounts');
            }
            return $user;
        }
        $username =
            ($this->userManager->checkUsername($response->getRealName()))
            ? $response->getRealName()
            : $response->getEmail()
        ;
        $user =
            (new Member())
            ->setUsername($username)
            ->setEmail($response->getEmail())
            ->{"set{$service}Id"}($response->getData()['id'])
            ->{"set{$service}AccessToken"}($response->getAccessToken())
        ;
        if ($response->getProfilePicture() !== null) {
            $this->setUserProfilePicture($user, $this->slugger->slugify($username), $response->getProfilePicture());
        }
        return $this->userManager->createOAuthUser($user);
    }
    
    protected function setUserProfilePicture(User $user, string $pictureName, string $profilePicture)
    {
        $extension = pathinfo($profilePicture, PATHINFO_EXTENSION);
        $path = "{$pictureName}.{$extension}";
        file_put_contents(Avatar::UPLOAD_DIR . "/{$path}", file_get_contents($profilePicture));
        $user->setAvatar(
            (new Avatar())
            ->setName($pictureName)
            ->setPath($path)
        );
    }

    protected function getOAuthService(ResourceOwnerInterface $resourceOwner): string
    {
        switch (get_class($resourceOwner)) {
            case GoogleResourceOwner::class: return 'Google';
            case FacebookResourceOwner::class: return 'Facebook';
            case GitHubResourceOwner::class: return 'Github';
            default: throw new \ErrorException('Unknown resource owner');
        }
    }
}