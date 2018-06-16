<?php

namespace App\EventListener;

use HWI\Bundle\OAuthBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccountConnectorListener
{
    /** @var UrlGeneratorInterface **/
    protected $router;
    
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
    
    public function onConnectConfirmed(GetResponseUserEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->router->generate('my_profile')));
    }
}