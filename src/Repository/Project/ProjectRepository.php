<?php

namespace App\Repository\Project;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function countAll()
    {
        return (int) $this
            ->createQueryBuilder('p')
            ->select('COUNT(p) as nb_projects')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}