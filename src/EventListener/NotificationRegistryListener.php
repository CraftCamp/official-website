<?php

namespace App\EventListener;

use App\Registry\NotificationRegistry;

use App\Manager\User\NotificationManager;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use App\Entity\User\User;

class NotificationRegistryListener
{
    /** @var NotificationRegistry **/
    protected $registry;
    /** @var NotificationManager **/
    protected $manager;
    /** @var TokenStorageInterface **/
    protected $tokenStorage;
    
    public function __construct(NotificationRegistry $registry, NotificationManager $manager, TokenStorageInterface $tokenStorage)
    {
        $this->registry = $registry;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequest()->getContentType() === 'json' ||
            $this->tokenStorage->getToken() === null ||
            !($user = $this->tokenStorage->getToken()->getUser()) instanceof User) {
            return;
        }
        $this->registry->storeNotifications($this->manager->getUserUnreadNotifications($user));
    }
}