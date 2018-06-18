<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users__product_owner")
 */
class ProductOwner extends User
{
    public function getType(): string
    {
        return self::TYPE_PRODUCT_OWNER;
    }
}
