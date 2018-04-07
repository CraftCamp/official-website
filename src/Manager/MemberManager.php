<?php

namespace App\Manager;

use App\Entity\User\Member;

use Doctrine\ORM\EntityManagerInterface;

class MemberManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @return int
     */
    public function countAll()
    {
        return $this->em->getRepository(Member::class)->countAll();
    }
}
