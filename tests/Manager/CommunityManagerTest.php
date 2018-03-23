<?php

namespace Tests\Manager;

use App\Manager\CommunityManager;

use App\Utils\Slugger;

use App\Entity\Community\Community;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class CommunityManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var CommunityManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new CommunityManager($this->getEntityManagerMock(), new Slugger());
    }
    
    public function testGetAll()
    {
        $communities = $this->manager->getAll();
        
        $this->assertCount(2, $communities);
        $this->assertInstanceOf(Community::class, $communities[0]);
    }
    
    public function getEntityManagerMock()
    {
        $entityManagerMock = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
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
        $entityManagerMock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback([$this, 'getRepositoryMock'])
        ;
        return $entityManagerMock;
    }
    
    public function getRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findAll')
            ->willReturnCallback([$this, 'getCommunitiesMock'])
        ;
        return $repositoryMock;
    }
    
    public function getCommunitiesMock()
    {
        return [
            (new Community())
            ->setId(1)
            ->setName('Symfony')
            ->setSlug('symfony'),
            (new Community())
            ->setId(2)
            ->setName('React')
            ->setSlug('react')
        ];
    }
}