<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users__beta_tester")
 */
class BetaTester extends User
{
    public function getType(): string
    {
        return self::TYPE_BETA_TESTER;
    }
}
