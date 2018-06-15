<?php

namespace App\Security\User;

use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use App\Manager\UserManager;

class AccountConnector implements AccountConnectorInterface
{
    /** @var UserManager **/
    protected $userManager;
    
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $service = ucfirst($response->getResourceOwner()->getName());
        if (($id = $user->{"get{$service}Id"}()) !== null && $id !== $response->getData()['id']) {
            throw new AuthenticationException('users.connection.account_mismatch');
        }
        $user
            ->{"set{$service}Id"}($response->getData()['id'])
            ->{"set{$service}AccessToken"}($response->getAccessToken())
        ;
        $this->userManager->updateUser($user);
    }
}