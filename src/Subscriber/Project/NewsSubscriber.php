<?php

namespace App\Subscriber\Project;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Event\Project\{
    AcceptedPollEvent,
    NewCommunityEvent,
    NewMemberEvent,
    NewReleaseEvent,
    NewRepositoryEvent
};

use App\Manager\Project\NewsManager;

use App\Entity\Project\News;

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
            AcceptedPollEvent::NAME => 'onAcceptedPoll',
            NewCommunityEvent::NAME => 'onNewCommunity',
            NewMemberEvent::NAME => 'onNewMember',
            NewReleaseEvent::NAME => 'onNewRelease',
            NewRepositoryEvent::NAME => 'onNewRepository',
        ];
    }
    
    public function onAcceptedPoll(AcceptedPollEvent $event)
    {
        $poll = $event->getPoll();
        
        $this->newsManager->create($poll->getProject(), News::CATEGORY_ACCEPTED_POLL, [
            '%score%' => $event->getScore(),
            '%nb_votes%' => $event->getNbVotes()
        ]);
    }
    
    public function onNewCommunity(NewCommunityEvent $event)
    {
        $this->newsManager->create($event->getProject(), News::CATEGORY_NEW_COMMUNITY, [
            '%name%' => $event->getCommunity()->getName()
        ]);
    }
    
    public function onNewMember(NewMemberEvent $event)
    {
        $this->newsManager->create($event->getProject(), News::CATEGORY_NEW_MEMBER, [
            '%username%' => $event->getUser()->getUsername()
        ]);
    }
    
    public function onNewRelease(NewReleaseEvent $event)
    {
        $this->newsManager->create($event->getProject(), News::CATEGORY_NEW_RELEASE, [
            '%version%' => $event->getVersion()
        ]);
    }
    
    public function onNewRepository(NewRepositoryEvent $event)
    {
        $this->newsManager->create($event->getProject(), News::CATEGORY_NEW_REPOSITORY, [
            '%name%' => $event->getRepository()->getName()
        ]);
    }
}