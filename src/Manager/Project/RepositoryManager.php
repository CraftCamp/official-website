<?php

namespace App\Manager\Project;

use Developtech\AgilityBundle\Entity\Repository\GithubRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RepositoryManager
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
    
    public function createRepository(string $owner, string $ownerType, string $name)
    {
        $repository =
            (new GithubRepository())
            ->setName($name)
            ->setOwner($owner)
            ->setOwnerType($ownerType)
        ;
        $this->em->persist($repository);
        $this->em->flush();
        $this->eventDispatcher->dispatch(RepositoryCreationEvent::NAME, new RepositoryCreationEvent($repository));
    }
}