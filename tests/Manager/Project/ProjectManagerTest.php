<?php

namespace Tests\Manager\Project;

use App\Manager\Project\ProjectManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use App\Entity\Project\Member;
use App\Entity\Project\Project;
use App\Entity\Organization;
use App\Entity\User\ProductOwner;

use App\Utils\Slugger;

class ProjectManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProjectManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new ProjectManager(
            $this->getEntityManagerMock(),
            $this->getEventDispatcherMock(),
            new Slugger()
        );
    }
    
    public function testCreate()
    {
        $project = $this->manager->createProject(
            'CraftCamp',
            'Association exceptionnelle',
            $this->getUserMock(),
            $this->getOrganizationMock()
        );
        
        $this->assertInstanceOf(Project::class, $project);
        $this->assertInstanceOf(ProductOwner::class, $project->getProductOwner());
        $this->assertInstanceOf(Organization::class, $project->getOrganization());
    }
    
    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage projects.existing_name
     */
    public function testCreateWithExistingName()
    {
        $this->manager->createProject('Pokemon', 'Super jeu', $this->getUserMock());
    }
    
    public function testJoinProject()
    {
        $membership = $this->manager->joinProject((new Project())->setId(1), (new ProductOwner())->setId(1));
        
        $this->assertInstanceOf(Member::class, $membership);
        $this->assertInstanceOf(Project::class, $membership->getProject());
        $this->assertInstanceOf(ProductOwner::class, $membership->getUser());
        $this->assertTrue($membership->getIsActive());
    }
    
    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage projects.already_joined
     */
    public function testJoinProjectWithMember()
    {
        $this->manager->joinProject((new Project())->setId(2), (new ProductOwner())->setId(1));
    }
    
    public function testGetProjectMembers()
    {
        $this->assertCount(2, $this->manager->getProjectMembers((new Project())->setId(1)));
    }
    
    public function testGetProjectMember()
    {
        $membership = $this->manager->getProjectMember((new Project())->setId(2), (new ProductOwner())->setId(1));
        
        $this->assertInstanceOf(Member::class, $membership);
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
    
    public function getRepositoryMock(string $repository)
    {
        return $this->{[
            Member::class => 'getMemberRepositoryMock',
            Project::class => 'getProjectRepositoryMock'
        ][$repository]}();
    }
    
    public function getProjectRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneByName'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneByName')
            ->willReturnCallback([$this, 'getProjectMock'])
        ;
        return $repositoryMock;
    }
    
    public function getProjectMock(string $name)
    {
        if ($name !== 'Pokemon') {
            return null;
        }
        return
            (new Project())
            ->setName($name)
        ;
    }
    
    public function getMemberRepositoryMock()
    {
        $repositoryMock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByProject', 'findOneBy'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturnCallback([$this, 'getMemberMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByProject')
            ->willReturnCallback([$this, 'getMembersMock'])
        ;
        return $repositoryMock;
    }
    
    public function getMemberMock($criterias)
    {
        if ($criterias['project']->getId() === 1) {
            return null;
        }
        return
            (new Member())
            ->setProject($criterias['project'])
            ->setUser($criterias['user'])
            ->setCreatedAt(new \DateTime())
            ->setUpatedAt(new \DateTime())
            ->setIsActive(true)
        ;
    }
    
    public function getMembersMock(Project $project)
    {
        return [
            (new Member())
            ->setProject($project)
            ->setCreatedAt(new \DateTime())
            ->setUpatedAt(new \DateTime())
            ->setIsActive(true),
            (new Member())
            ->setProject($project)
            ->setCreatedAt(new \DateTime())
            ->setUpatedAt(new \DateTime())
            ->setIsActive(true)
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
    
    public function getUserMock()
    {
        return
            (new ProductOwner())
            ->setUsername('Toto')
        ;
    }
    
    public function getOrganizationMock()
    {
        return
            (new Organization())
            ->setName('Testing area')
        ;
    }
}