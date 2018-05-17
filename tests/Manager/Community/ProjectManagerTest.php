<?php

namespace Tests\Manager\Community;

use App\Manager\Community\ProjectManager;

use App\Entity\Community\Community;
use App\Entity\Community\Project as CommunityProject;
use App\Entity\Project\Project;

class ProjectManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProjectManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new ProjectManager($this->getEntityManagerMock());
    }
    
    public function testGetCommunityProjects()
    {
        $projects = $this->manager->getCommunityProjects((new Community()));
        
        $this->assertCount(3, $projects);
        $this->assertInstanceOf(CommunityProject::class, $projects[0]);
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
            ->willReturnCallback([$this, 'getCommunityProjectsMock'])
        ;
        return $repositoryMock;
    }
    
    public function getCommunityProjectsMock(Community $community)
    {
        return [
            (new CommunityProject())
            ->setProject((new Project())->setName('CraftCamp'))
            ->setCommunity($community),
            (new CommunityProject())
            ->setProject((new Project())->setName('Terra'))
            ->setCommunity($community),
            (new CommunityProject())
            ->setProject((new Project())->setName('Alziin'))
            ->setCommunity($community),
        ];
    }
}