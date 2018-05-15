<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\MemberRepository")
 * @ORM\Table(name="users__member")
 */
class Member extends User
{
    public function getType(): string
    {
        return self::TYPE_MEMBER;
    }
}
