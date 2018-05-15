<?php

namespace App\Manager\Community;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Entity\Community\{
    Community,
    Member
};
use App\Entity\User\User;

use App\Event\Community\NewMemberEvent;

class MemberManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;


    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function getCommunityMembers(Community $community): array
    {
        return $this->em->getRepository(Member::class)->findByCommunity($community);
    }
    
    public function getMemberCommunities(User $user): array
    {
        return $this->em->getRepository(Member::class)->findByUser($user);
    }
    
    public function createMembership(Community $community, User $user, bool $isLead = false, bool $isNews = true): Member
    {
        $membership =
            (new Member())
            ->setCommunity($community)
            ->setUser($user)
            ->setIsLead($isLead)
        ;
        $this->em->persist($membership);
        $this->em->flush($membership);
        if ($isNews === true) {
            $this->eventDispatcher->dispatch(NewMemberEvent::NAME, new NewMemberEvent($community, $user));
        }
        return $membership;
    }
}