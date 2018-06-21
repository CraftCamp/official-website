<?php

namespace App\Repository\User;

use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function read(array $ids = [])
    {
        return $this
            ->createQueryBuilder('n')
            ->update()
            ->set('n.readAt', ':read_at')
            ->where('n.id IN (:ids)')
            ->setParameters([
                'ids' => $ids,
                'read_at' => new \DateTime()
            ])
            ->getQuery()
            ->execute()
        ;
    }
}