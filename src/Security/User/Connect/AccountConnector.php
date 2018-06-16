<?php

namespace App\Security\User\Connect;

use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Manager\UserManager;
use App\Entity\User\User;

class AccountConnector implements AccountConnectorInterface
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var UserManager **/
    protected $userManager;
    
    public function __construct(EntityManagerInterface $em, UserManager $userManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
    }
    
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $service = ucfirst($response->getResourceOwner()->getName());
        $serviceUserId = $response->getData()['id'];
        
        if (($id = $user->{"get{$service}Id"}()) !== null && $id !== $serviceUserId) {
            throw new ConnectException('users.connection.account_mismatch');
        } elseif ($id === null && $this->em->getRepository(User::class)->{"findOneBy{$service}Id"}($serviceUserId) !== null) {
            throw new ConnectException('users.connection.account_already_bound');
        }
        $user
            ->{"set{$service}Id"}($response->getData()['id'])
            ->{"set{$service}AccessToken"}($response->getAccessToken())
        ;
        $this->userManager->updateUser($user);
    }
}