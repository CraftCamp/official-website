<?php

namespace Tests\Manager\Project;

use App\Manager\Project\DetailsManager;

use App\Entity\Project\Project;
use App\Entity\Project\Details;

class DetailsManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var DetailsManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new DetailsManager($this->getEntityManagerMock());
    }
    
    public function testGetProjectDetails()
    {
        $details = $this->manager->getProjectDetails((new Project())->setId(1));
        
        $this->assertInstanceOf(Details::class, $details);
        $this->assertEquals(1, $details->getProject()->getId());
        $this->assertEquals('Conquer the world', $details->getGoalDescription());
    }
    
    public function testGetUnexistingProjectDetails()
    {
        $this->assertNull($this->manager->getProjectDetails((new Project())->setId(3)));
    }
    
    public function testPutExistingProjectDetails()
    {
        $details = $this->manager->putProjectDetails((new Project())->setId(1), [
            'need_description' => 'Lots of money',
            'target_description' => 'Everyone',
            'goal_description' => 'Buy the world'
        ]);
        $this->assertInstanceOf(Details::class, $details);
        $this->assertEquals('Lots of money', $details->getNeedDescription());
        $this->assertEquals('Everyone', $details->getTargetDescription());
        $this->assertEquals('Buy the world', $details->getGoalDescription());
    }
    
    public function testPutNewProjectDetails()
    {
        $details = $this->manager->putProjectDetails((new Project())->setId(3), [
            'need_description' => 'Lots of money',
            'target_description' => 'Everyone',
            'goal_description' => 'Buy the world'
        ]);
        $this->assertInstanceOf(Details::class, $details);
        $this->assertEquals('Lots of money', $details->getNeedDescription());
        $this->assertEquals('Everyone', $details->getTargetDescription());
        $this->assertEquals('Buy the world', $details->getGoalDescription());
    }
    
    public function getEntityManagerMock()
    {
        $entityManagerMock = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        
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
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturnCallback([$this, 'getDetailsMock'])
        ;
        return $repositoryMock;
    }
    
    public function getDetailsMock(array $criterias)
    {
        if ($criterias['project']->getId() === 3) {
            return null;
        }
        return
            (new Details())
            ->setProject($criterias['project'])
            ->setNeedDescription('Besoin de rien, envie de toi')
            ->setTargetDescription('Delta 3B 456 Sud-Sud-Ouest')
            ->setGoalDescription('Conquer the world')
        ;
    }
}