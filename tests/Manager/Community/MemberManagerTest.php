<?php

namespace Tests\Manager\Community;

use App\Manager\Community\MemberManager;

use App\Entity\Community\Member as CommunityMember;
use App\Entity\Community\Community;
use App\Entity\User\Member;

class MemberManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var MemberManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new MemberManager(
            $this->getEntityManagerMock(),
            $this->getEventDispatcherMock()
        );
    }
    
    public function testCreateMembership()
    {
        $membership = $this->manager->createMembership(
            (new Community())->setId(1)->setName('Golang'),
            (new Member())->setId(1)->setUsername('Toto')
        );
        $this->assertInstanceOf(CommunityMember::class, $membership);
        $this->assertFalse($membership->getIsLead());
        $this->assertEquals('Golang', $membership->getCommunity()->getName());
        $this->assertEquals('Toto', $membership->getUser()->getUsername());
    }
    
    public function testCreateLeadMembership()
    {
        $membership = $this->manager->createMembership(
            (new Community())->setId(1)->setName('Golang'),
            (new Member())->setId(1)->setUsername('Toto'),
            true
        );
        $this->assertInstanceOf(CommunityMember::class, $membership);
        $this->assertTrue($membership->getIsLead());
    }
    
    public function testGetCommunityMembers()
    {
        $members = $this->manager->getCommunityMembers((new Community())->setId(1)->setName('Golang'));
        
        $this->assertCount(4, $members);
        $this->assertInstanceOf(CommunityMember::class, $members[0]);
        $this->assertEquals('Golang', $members[1]->getCommunity()->getName());
    }
    
    public function testGetMemberCommunities()
    {
        $communities = $this->manager->getMemberCommunities((new Member())->setId(1)->setUsername('Toto'));
        
        $this->assertCount(2, $communities);
        $this->assertInstanceOf(CommunityMember::class, $communities[0]);
        $this->assertEquals('Toto', $communities[0]->getUser()->getUsername());
    }
    
    public function getEntityManagerMock()
    {
        $entityManagerMock = $this
            ->getMockBuilder(\Doctrine\ORM\EntityManagerInterface::class)
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
    
    public function getRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByCommunity', 'findByUser'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByCommunity')
            ->willReturnCallback([$this, 'getCommunityMembersMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByUser')
            ->willReturnCallback([$this, 'getMemberCommunitiesMock'])
        ;
        return $repositoryMock;
    }
    
    public function getCommunityMembersMock(Community $community)
    {
        return [
            (new CommunityMember())
            ->setCommunity($community)
            ->setUser((new Member())->setId(1)->setUsername('Toto')),
            (new CommunityMember())
            ->setCommunity($community)
            ->setUser((new Member())->setId(2)->setUsername('John Doe')),
            (new CommunityMember())
            ->setCommunity($community)
            ->setUser((new Member())->setId(3)->setUsername('Tata')),
            (new CommunityMember())
            ->setCommunity($community)
            ->setUser((new Member())->setId(4)->setUsername('Foo')),
        ];
    }
    
    public function getMemberCommunitiesMock(Member $member)
    {
        return [
            (new CommunityMember())
            ->setCommunity((new Community())->setId(1)->setName('Golang'))
            ->setUser($member),
            (new CommunityMember())
            ->setCommunity((new Community())->setId(2)->setName('Symfony'))
            ->setUser($member),
        ];
    }
    
    public function getEventDispatcherMock()
    {
        $eventDispatcherMock = $this
            ->getMockBuilder(\Symfony\Component\EventDispatcher\EventDispatcherInterface::class)
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