<?php

namespace App\Entity\User;

use App\Entity\Organization;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users__product_owner")
 */
class ProductOwner extends User
{
}
