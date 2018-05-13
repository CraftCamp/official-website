<?php

namespace App\Manager\Community;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Community\{
    Community,
    Project
};

class ProjectManager
{
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getCommunityProjects(Community $community): array
    {
        return $this->em->getRepository(Project::class)->findByCommunity($community);
    }
}