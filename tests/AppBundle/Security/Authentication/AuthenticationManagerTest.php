<?php

namespace Tests\AppBundle\Security\Authentication;

use AppBundle\Security\Authentication\AuthenticationManager;

use AppBundle\Security\User\Provider\UserProvider;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Symfony\Component\Security\Core\Role\Role;

use AppBundle\Entity\User\ProductOwner;

class AuthenticationManagerTest extends \PHPUnit_Framework_TestCase {
    /** @var \AppBundle\Security\Authentication\AuthenticationManager **/
    protected $authenticationManager;

    public function setUp() {
        $this->authenticationManager = new AuthenticationManager(
            $this->getRequestStackMock(),
            $this->getTokenStorageMock(),
            $this->getEncoderFactoryMock(),
            $this->getUserCheckerMock(),
            $this->getSessionMock(),
            $this->getUserProviderMock(),
            $this->getEventDispatcherMock(),
            'main'
        );
    }

    public function testAuthenticate() {
        $token = $this->getTokenMock();

        $authenticatedToken = $this->authenticationManager->authenticate($token);

        $this->assertEquals(UsernamePasswordToken::class, get_class($authenticatedToken));
        $this->assertTrue($authenticatedToken->isAuthenticated());
        $this->assertCount(3, $authenticatedToken->getRoles());
    }

    public function getTokenMock() {
        $tokenMock = $this
            ->getMockBuilder(UsernamePasswordToken::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $tokenMock
            ->expects($this->any())
            ->method('getUser')
            ->willReturnCallback([$this, 'getUserMock'])
        ;
        $tokenMock
            ->expects($this->any())
            ->method('getProviderKey')
            ->willReturn('main')
        ;
        $tokenMock
            ->expects($this->any())
            ->method('getRoles')
            ->willReturnCallback([$this, 'getRolesMock'])
        ;
        $tokenMock
            ->expects($this->any())
            ->method('getAttributes')
            ->willReturn([])
        ;
        return $tokenMock;
    }

    public function getRolesMock() {
        return [
            new Role('ROLE_USER'),
            new Role('ROLE_MEMBER'),
            new Role('ROLE_ADMIN')
        ];
    }

    public function getUserMock() {
        return
            (new ProductOwner())
            ->setUsername('John Doe')
            ->addRole('ROLE_USER')
            ->addRole('ROLE_MEMBER')
            ->addRole('ROLE_ADMIN')
        ;
    }

    public function getRequestStackMock() {
        $requestStackMock = $this
            ->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $requestStackMock
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturnCallback([$this, 'getRequestMock'])
        ;
        return $requestStackMock;
    }

    public function getRequestMock() {
        $requestMock = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $requestMock;
    }

    public function getTokenStorageMock() {
        $tokenStorageMock = $this
            ->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $tokenStorageMock
            ->expects($this->any())
            ->method('setToken')
            ->willReturn(true)
        ;
        return $tokenStorageMock;
    }

    public function getEncoderFactoryMock() {
        $encoderFactoryMock = $this
            ->getMockBuilder(EncoderFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $encoderFactoryMock;
    }

    public function getUserCheckerMock() {
        $userCheckerMock = $this
            ->getMockBuilder(UserChecker::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userCheckerMock
            ->expects($this->any())
            ->method('checkPreAuth')
            ->willReturn(true)
        ;
        $userCheckerMock
            ->expects($this->any())
            ->method('checkPostAuth')
            ->willReturn(true)
        ;
        return $userCheckerMock;
    }

    public function getSessionMock() {
        $sessionMock = $this
            ->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $sessionMock
            ->expects($this->any())
            ->method('set')
            ->willReturn(true)
        ;
        return $sessionMock;
    }

    public function getUserProviderMock() {
        $userProviderMock = $this
            ->getMockBuilder(UserProvider::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        return $userProviderMock;
    }

    public function getEventDispatcherMock() {
        $eventDispatcherMock = $this
            ->getMockBuilder(EventDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $eventDispatcherMock
            ->expects($this->any())
            ->method('dispatch')
            ->willReturn(true)
        ;
        return $eventDispatcherMock;
    }
}
