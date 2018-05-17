<?php

namespace Tests\Manager\Community;

use App\Manager\Community\NewsManager;

use App\Entity\Community\Community;
use App\Entity\Community\News;

class NewsManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var NewsManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new NewsManager($this->getEntityManagerMock());
    }
    
    public function testGetCommunityNews()
    {
        $news = $this->manager->getCommunityNews((new Community()));
        
        $this->assertCount(3, $news);
        $this->assertInstanceOf(News::class, $news[0]);
    }
    
    public function testCreate()
    {
        $news = $this->manager->create((new Community()), News::CATEGORY_NEW_PROJECT, [
            'name' => 'CraftCamp',
        ]);
        $this->assertInstanceOf(Community::class, $news->getCommunity());
        $this->assertEquals(News::CATEGORY_NEW_PROJECT, $news->getCategory());
        $this->assertEquals([
            'name' => 'CraftCamp'
        ], $news->getData());
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
            ->setMethods(['findByCommunity'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByCommunity')
            ->willReturnCallback([$this, 'getNewsMock'])
        ;
        return $repositoryMock;
    }
    
    public function getNewsMock()
    {
        return [
            (new News())
            ->setId(1)
            ->setCategory(News::CATEGORY_COMMUNITY_CREATION),
            (new News())
            ->setId(2)
            ->setCategory(News::CATEGORY_NEW_MEMBER),
            (new News())
            ->setId(3)
            ->setCategory(News::CATEGORY_NEW_MEMBER),
        ];
    }
}