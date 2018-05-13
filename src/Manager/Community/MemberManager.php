<?php

namespace App\Manager\Community;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Community\{
    Community,
    Member
};

class MemberManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function getCommunityMembers(Community $community): array
    {
        return $this->em->getRepository(Member::class)->findByCommunity($community);
    }
}