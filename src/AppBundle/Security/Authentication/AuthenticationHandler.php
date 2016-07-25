<?php

namespace AppBundle\Security\Authentication;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\{Request, RedirectResponse};

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Translation\TranslatorInterface;

use Symfony\Component\HttpFoundation\Session\Session;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, LogoutSuccessHandlerInterface {
    /** @var \Symfony\Component\HttpFoundation\Session\Session **/
    protected $session;
    /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router **/
    protected $router;
    /** @var \Symfony\Component\Translation\TranslatorInterface **/
    protected $translator;
    
    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(Session $session, Router $router, TranslatorInterface $translator) {
        $this->session = $session;
        $this->router = $router;
        $this->translator = $translator;
    }
    
    /**
     * @param \AppBundle\Security\Authentication\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return \AppBundle\Security\Authentication\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        $this->session->getFlashbag()->add('success', $this->translator->trans('users.connection.success', ['%username%' => $token->getUsername()]));
        
        return new RedirectResponse($this->router->generate('dashboard'));
    }
    
    /**
     * @param \AppBundle\Security\Authentication\Request $request
     * @return \AppBundle\Security\Authentication\RedirectResponse
     */
    public function onLogoutSuccess(Request $request) {
        $this->session->getFlashbag()->add('success', $this->translator->trans('users.logout.success'));
        
        return new RedirectResponse($this->router->generate('homepage'));
    }
}