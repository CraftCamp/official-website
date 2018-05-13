<?php

namespace App\Manager\Community;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Community\Community;
use App\Entity\Picture;

use App\Utils\Slugger;

class CommunityManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var Slugger **/
    protected $slugger;
    
    public function __construct(EntityManagerInterface $entityManager, Slugger $slugger)
    {
        $this->em = $entityManager;
        $this->slugger = $slugger;
    }
    
    public function createCommunity(string $name, $picture = null): Community
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