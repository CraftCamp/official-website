<?php

namespace App\Subscriber\Community;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Event\Community\{
    CommunityCreationEvent,
    NewMemberEvent
};

use App\Manager\Community\NewsManager;

use App\Entity\Community\News;

class NewsSubscriber implements EventSubscriberInterface
{
    /** @var NewsManager **/
    protected $newsManager;
    
    public function __construct(NewsManager $newsManager)
    {
        $this->newsManager = $newsManager;
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            CommunityCreationEvent::NAME => 'onCommunityCreation',
            NewMemberEvent::NAME => 'onNewMember',
        ];
    }
    
    public function onCommunityCreation(CommunityCreationEvent $event)
    {
        $this->newsManager->create($event->getCommunity(), News::CATEGORY_COMMUNITY_CREATION, [
            '%username%' => $event->getFounder()->getUsername()
        ]);
    }
    
    public function onNewMember(NewMemberEvent $event)
    {
        $this->newsManager->create($event->getCommunity(), News::CATEGORY_NEW_MEMBER, [
            '%username%' => $event->getUser()->getUsername()
        ]);
    }
}