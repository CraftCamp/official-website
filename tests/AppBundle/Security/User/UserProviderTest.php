<?php

namespace Tests\AppBundle\Security\User;

use AppBundle\Security\User\Provider\UserProvider;

use AppBundle\Manager\UserManager;

use AppBundle\Entity\User\ProductOwner;

class UserProviderTest extends \PHPUnit_Framework_TestCase {
    /** @var \AppBundle\Security\User\Provider\UserProvider **/
    protected $provider;

    public function setUp() {
        $this->provider = new UserProvider($this->getUserManagerMock());
    }

    public function testLoadUserByUsername() {
        $user = $this->provider->loadUserByUsername('John Doe');

        $this->assertInstanceOf(ProductOwner::class, $user);
        $this->assertEquals(1, $user->getId());
        $this->assertTrue($user->isEnabled());
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadInvalidUserByUsername() {
        $this->provider->loadUserByUsername('toto');
    }

    public function testRefreshUser() {
        $user = $this->provider->refreshUser((new ProductOwner())->setUsername('John Doe'));

        $this->assertInstanceOf(ProductOwner::class, $user);
        $this->assertEquals(1, $user->getId());
        $this->assertTrue($user->isEnabled());
    }

    public function testValidSupportsClass() {
        $this->assertTrue($this->provider->supportsClass(ProductOwner::class));
    }

    public function getUserManagerMock() {
        $userManagerMock = $this
            ->getMockBuilder(UserManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userManagerMock
            ->expects($this->any())
            ->method('findUserByUsernameOrEmail')
            ->willReturnCallback([$this, 'getUserByUsernameMock'])
        ;
        return $userManagerMock;
    }

    public function getUserByUsernameMock($username) {
        if($username === 'John Doe') {
            return
                (new ProductOwner())
                ->setId(1)
                ->setUsername('John Doe')
                ->enable(true)
            ;
        }
        return null;
    }
}
