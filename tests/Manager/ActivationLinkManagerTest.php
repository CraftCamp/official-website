<?php

namespace Tests\Manager;

use App\Manager\ActivationLinkManager;

use Doctrine\ORM\EntityManager;

use App\Entity\User\{ProductOwner, ActivationLink};

use App\Utils\Mailer;

class ActivationLinkManagerTest extends \PHPUnit\Framework\TestCase {
    /** @var \App\Manager\ActivationLinkManager **/
    protected $manager;

    public function setUp() {
        $this->manager = new ActivationLinkManager(
            $this->getEntityManagerMock(),
            $this->getMailerMock()
        );
    }

    public function testCreateActivationLink() {
        $user = new ProductOwner();
        $this->manager->createActivationLink($user);

        $this->assertInstanceOf(ActivationLink::class, $user->getActivationLink());
    }

    public function testSendValidationMail() {
        $user = new ProductOwner();
        $this->manager->createActivationLink($user);

        $this->assertTrue($this->manager->sendValidationMail($user));
    }

    public function testFindOneByHash() {
        $activationLink = $this->manager->findOneByHash('test-hash');

        $this->assertInstanceOf(ActivationLink::class, $activationLink);
        $this->assertEquals(1, $activationLink->getId());
        $this->assertEquals('test-hash', $activationLink->getHash());
        $this->assertInstanceOf(\DateTime::class, $activationLink->getCreatedAt());
    }

    public function getEntityManagerMock() {
        $entityManagerMock = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback([$this, 'getRepositoryMock'])
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('persist')
            ->willReturn(true)
        ;
        $entityManagerMock
            ->expects($this->any())
            ->method('flush')
            ->willReturn(true)
        ;
        return $entityManagerMock;
    }

    public function getRepositoryMock() {
        $repositoryMock = $this
            ->getMockBuilder('App\Repository\ActivationLinkRepository')
            ->disableOriginalConstructor()
            ->setMethods(['findOneByHash'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneByHash')
            ->willReturnCallback([$this, 'getActivationLinkMock'])
        ;
        return $repositoryMock;
    }

    public function getActivationLinkMock() {
        return
            (new ActivationLink())
            ->setId(1)
            ->setHash('test-hash')
            ->setCreatedAt(new \DateTime())
        ;
    }

    public function getMailerMock() {
        $mailerMock = $this
            ->getMockBuilder(Mailer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $mailerMock
            ->expects($this->any())
            ->method('sendTo')
            ->willReturn(1)
        ;
        return $mailerMock;
    }
}
