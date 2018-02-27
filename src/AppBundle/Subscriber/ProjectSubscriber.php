<?php

namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Developtech\AgilityBundle\Event\ProjectEvent;

class ProjectSubscriber implements EventSubscriberInterface {
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ProjectEvent::NAME => 'handleProjectEvent'
        ];
    }

    /**
     * @param ProjectEvent $event
     */
    public function handleProjectEvent(ProjectEvent $event)
    {
        switch ($event->getType()) {
            case ProjectEvent::TYPE_CREATION: $this->handleProjectCreation($event);
            case ProjectEvent::TYPE_UPDATE: $this->handleProjectUpdate($event);
            case ProjectEvent::TYPE_REMOVAL: $this->handleProjectRemoval($event);
        }
    }

    /**
     * @param ProjectEvent $event
     */
    public function handleProjectCreation(ProjectEvent $event)
    {

    }

    /**
     * @param ProjectEvent $event
     */
    public function handleProjectUpdate(ProjectEvent $event)
    {

    }

    /**
     * @param ProjectEvent $event
     */
    public function handleProjectRemoval(ProjectEvent $event)
    {

    }
}
