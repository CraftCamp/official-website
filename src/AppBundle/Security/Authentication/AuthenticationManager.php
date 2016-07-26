<?php

namespace AppBundle\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthenticationManager implements AuthenticationManagerInterface {
    /** @var \Symfony\Component\HttpFoundation\Request **/
    protected $request;
    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface **/
    protected $tokenStorage;
    /** @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface **/
    protected $encoderFactory;
    /** @var \Symfony\Component\Security\Core\User\UserChecker **/
    protected $userChecker;
    /** @var \Symfony\Component\HttpFoundation\Session\Session **/
    protected $session;
    /** @var \Symfony\Component\Security\Core\User\UserProviderInterface **/
    protected $userProvider;
    /** @var \Symfony\Component\EventDispatcher\EventDispatcher **/
    protected $eventDispatcher;
    /** @var string **/
    protected $firewall;
    
    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \AppBundle\Security\EventDispatcherInterface $eventDispatcher
     * @param string $firewall
     */
    public function __construct(
        RequestStack $request,
        TokenStorageInterface $tokenStorage,
        EncoderFactoryInterface $encoderFactory,
        UserChecker $userChecker,
        Session $session,
        UserProviderInterface $userProvider,
        EventDispatcherInterface $eventDispatcher,
        string $firewall
    ) {
        $this->request = $request->getCurrentRequest();
        $this->tokenStorage = $tokenStorage;
        $this->encoderFactory = $encoderFactory;
        $this->userChecker = $userChecker;
        $this->session = $session;
        $this->userProvider = $userProvider;
        $this->eventDispatcher = $eventDispatcher;
        $this->firewall = $firewall;
    }
    
    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token) {
        $provider = new DaoAuthenticationProvider(
            $this->userProvider,
            $this->userChecker,
            $this->firewall,
            $this->encoderFactory
        );
        $authenticationProviderManager = new AuthenticationProviderManager([$provider]);
        $authenticatedToken = $authenticationProviderManager->authenticate($token);

        $this->tokenStorage->setToken($authenticatedToken); //now the user is logged in
        $this->session->set("_{$this->firewall}", serialize($authenticatedToken));
        //now dispatch the login event
        $event = new InteractiveLoginEvent($this->request, $authenticatedToken);
        $this->eventDispatcher->dispatch('security.interactive_login', $event);
        
        return $authenticatedToken;
    }
}

