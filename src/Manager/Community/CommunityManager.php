<?php

namespace App\Manager\Community;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Entity\Community\Community;
use App\Entity\Picture;
use App\Entity\User\User;

use App\Utils\Slugger;

use App\Event\Community\CommunityCreationEvent;

class CommunityManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    /** @var MemberManager **/
    protected $memberManager;
    /** @var Slugger **/
    protected $slugger;
    
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, MemberManager $memberManager, Slugger $slugger)
    {
        $this->em = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->memberManager = $memberManager;
        $this->slugger = $slugger;
    }
    
    public function createCommunity(User $founder, string $name, $picture = null): Community
    {
        $community =
            (new Community())
            ->setName($name)
            ->setSlug($this->slugger->slugify($name))
        ;
        if ($picture !== null) {
            $community->setPicture(
                (new Picture())
                ->setName("community-{$community->getSlug()}")
                ->setFile($picture)
            );
        }
        $this->em->persist($community);
        $this->em->flush($community);
        $this->memberManager->createMembership($community, $founder, true, false);
        $this->eventDispatcher->dispatch(CommunityCreationEvent::NAME, new CommunityCreationEvent($community, $founder));
        return $community;
    }
    
    public function countAll(): int
    {
        return $this->em->getRepository(Community::class)->countAll();
    }
    
    public function getAll(): array
    {
        return $this->em->getRepository(Community::class)->findAll();
    }
    
    public function get(string $slug): Community
    {
        return $this->em->getRepository(Community::class)->findOneBySlug($slug);
    }
}