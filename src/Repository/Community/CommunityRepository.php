<?php

namespace App\Repository\Community;

use Doctrine\ORM\EntityRepository;

class CommunityRepository extends EntityRepository
{
    public function countAll()
    {
        return (int) $this
            ->createQueryBuilder('c')
            ->select('COUNT(c) as nb_communities')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}