<?php

namespace App\Manager;

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
    
    /**
     * @param EntityManagerInterface $entityManager
     */
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
    
    public function getAll()
    {
        return $this->em->getRepository(Community::class)->findAll();
    }
}