<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\User\{
    User,
    ProductOwner
};

use AppBundle\DataFixtures\ORM\User\{
    LoadProductOwnerData,
    LoadActivationLinkData
};
use AppBundle\DataFixtures\ORM\LoadOrganizationData;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase {
    /** @var \AppBundle\Repository\UserRepository **/
    protected $repository;

    public function setUp() {
        $this->repository = $this->getContainer()->get('doctrine')->getRepository(User::class);

        $this->loadFixtures([
            LoadActivationLinkData::class,
            LoadOrganizationData::class,
            LoadProductOwnerData::class
        ]);
    }

    public function testCheckUsername() {
        $this->assertFalse($this->repository->checkUsername('kern'));
    }

    public function testCheckUsernameWithUnexistingUsername() {
        $this->assertTrue($this->repository->checkUsername('Zorg'));
    }

    public function testCheckEmail() {
        $this->assertFalse($this->repository->checkEmail('test@gmail.com'));
    }

    public function testCheckEmailWithUnexistingEmail() {
        $this->assertTrue($this->repository->checkEmail('zorg@gmail.com'));
    }

    public function testFindOneByUsernameOrEmailWithUsername() {
        $user = $this->repository->findOneByUsernameOrEmail('test');

        $this->assertInstanceOf(ProductOwner::class, $user);
        $this->assertEquals('test@gmail.com', $user->getEmail());
    }

    public function testFindOneByUsernameOrEmailWithEmail() {
        $user = $this->repository->findOneByUsernameOrEmail('test@gmail.com');

        $this->assertInstanceOf(ProductOwner::class, $user);
        $this->assertEquals('test', $user->getUsername());
    }

    public function testFindOneByUsernameOrEmailWithInvalidIdentifier() {
        $this->assertNull($this->repository->findOneByUsernameOrEmail('unknown_email@example.org'));
    }
}
