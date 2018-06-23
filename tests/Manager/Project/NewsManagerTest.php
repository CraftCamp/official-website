<?php

namespace Tests\Manager\Project;

use App\Manager\Project\NewsManager;

use App\Entity\Project\{
    News,
    Project
};

class NewsManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var NewsManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new NewsManager($this->getEntityManagerMock());
    }
    
    public function testGetProjectNews()
    {
        $project = (new Project())->setName('CraftCamp');
        $news = $this->manager->getProjectNews($project);
        
        $this->assertCount(2, $news);
        $this->assertInstanceOf(News::class, $news[0]);
        $this->assertEquals('poll.new', $news[0]->getCategory());
        $this->assertEquals($project, $news[0]->getProject());
        $this->assertEquals([
            '%project_name%' => 'CraftCamp',
            '%score%' => 56
        ], $news[1]->getData());
    }
    
    public function testCreate()
    {
        $project = (new Project())->setName('CraftCamp');
        $news = $this->manager->create($project, 'poll.new', [
            '%project_name%' => $project->getName()
        ]);
        $this->assertInstanceOf(News::class, $news);
        $this->assertEquals($project, $news->getProject());
        $this->assertEquals('poll.new', $news->getCategory());
        $this->assertEquals([
            '%project_name%' => 'CraftCamp'
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
            ->getMockBuilder(\Doctrine\ORM\EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByProject'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByProject')
            ->willReturnCallback([$this, 'getNewsMock'])
        ;
        return $repositoryMock;
    }
    
    public function getNewsMock(Project $project)
    {
        return [
            (new News())
            ->setProject($project)
            ->setCategory('poll.new')
            ->setData([
                '%project_name%' => $project->getName()
            ]),
            (new News())
            ->setProject($project)
            ->setCategory('poll.success')
            ->setData([
                '%project_name%' => $project->getName(),
                '%score%' => 56
            ]),
        ];
    }
}