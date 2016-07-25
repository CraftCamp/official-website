<?php

namespace Tests\AppBundle\Entity\User;

use AppBundle\Entity\Organization;
use AppBundle\Entity\User\{
    ProductOwner,
    ActivationLink
};

class ProductOwnerTest extends \PHPUnit_Framework_TestCase {
    public function testEntity() {
        $user =
            (new ProductOwner())
            ->setId(1)
            ->setUsername('John Doe')
            ->setPlainPassword('toto123')
            ->setPassword('hardcore_password')
            ->setSalt('hardcore_salt')
            ->setEmail('john_doe@gmail.com')
            ->addRole('ROLE_USER')
            ->addRole('ROLE_ADMIN')
            ->removeRole('ROLE_USER')
            ->setOrganization((new Organization()))
            ->enable(true)
            ->setActivationLink(new ActivationLink())
            ->setIsLocked(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
        ;
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('John Doe', $user->getUsername());
        $this->assertEquals('toto123', $user->getPlainPassword());
        $this->assertEquals('hardcore_password', $user->getPassword());
        $this->assertEquals('hardcore_salt', $user->getSalt());
        $this->assertEquals('john_doe@gmail.com', $user->getEmail());
        $this->assertCount(1, $user->getRoles());
        $this->assertTrue($user->hasRole('ROLE_ADMIN'));
        $this->assertTrue($user->isEnabled());
        $this->assertFalse($user->getIsLocked());
        $this->assertInstanceOf(Organization::class, $user->getOrganization());
        $this->assertTrue($user->eraseCredentials());
        $this->assertInstanceOf(ActivationLink::class, $user->getActivationLink());
        $this->assertInstanceOf('DateTime', $user->getCreatedAt());
        $this->assertInstanceOf('DateTime', $user->getUpdatedAt());
    }
}
