<?php

namespace App\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\User\User;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationManager
{
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    /** @var SessionInterface **/
    protected $session;
    /** @var TokenStorageInterface **/
    protected $tokenStorage;
    
    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }
    
    /**
     * @param Request $request
     * @param User $user
     * @param string $firewall
     */
    public function authenticate(Request $request, User $user, $firewall = 'main')
    {
        $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        $this->tokenStorage->setToken($token);

        // If the firewall name is not main, then the set value would be instead:
        // $this->get('session')->set('_security_XXXFIREWALLNAMEXXX', serialize($token));
        $this->session->set("_security_{$firewall}", serialize($token));
        
        // Fire the login event manually
        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch("security.interactive_login", $event);
    }
}