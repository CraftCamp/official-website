<?php

namespace App\Repository\User;

use Doctrine\ORM\EntityRepository;

class MemberRepository extends EntityRepository
{
    public function countAll()
    {
        return (int) $this
            ->createQueryBuilder('m')
            ->select('COUNT(m) as nb_members')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}