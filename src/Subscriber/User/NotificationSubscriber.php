<?php

namespace App\Subscriber\User;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Event\Project\NewPollEvent;

use App\Manager\User\NotificationManager;

class NotificationSubscriber implements EventSubscriberInterface
{
    /** @var NotificationManager **/
    protected $notificationManager;
    
    public function __construct(NotificationManager $notificationManager)
    {
        $this->notificationManager = $notificationManager;
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            NewPollEvent::NAME => 'onNewPoll',
        ];
    }
    
    public function onNewPoll(NewPollEvent $event)
    {
        $this->notificationManager->notifyAllMembers('projects.votes.notification', [
            '%project%' => $event->getPoll()->getProject()->getName(),
            '%poll_id%' => $event->getPoll()->getId()
        ]);
    }
}