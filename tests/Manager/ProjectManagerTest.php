<?php

namespace Tests\Manager;

use App\Manager\ProjectManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use App\Entity\Project\Membership;
use App\Entity\Project\Project;
use App\Entity\User\ProductOwner;

class ProjectManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var ProjectManager **/
    protected $manager;
    
    public function setUp()
    {
        $this->manager = new ProjectManager($this->getEntityManagerMock());
    }
    
    public function testJoinProject()
    {
        $membership = $this->manager->joinProject((new Project())->setId(1), (new ProductOwner())->setId(1));
        
        $this->assertInstanceOf(Membership::class, $membership);
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
    
    public function testGetProjectMembership()
    {
        $membership = $this->manager->getProjectMembership((new Project())->setId(2), (new ProductOwner())->setId(1));
        
        $this->assertInstanceOf(Membership::class, $membership);
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
            ->setMethods(['findByProject', 'findOneBy'])
            ->getMock()
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturnCallback([$this, 'getMembershipMock'])
        ;
        $repositoryMock
            ->expects($this->any())
            ->method('findByProject')
            ->willReturnCallback([$this, 'getMembershipsMock'])
        ;
        return $repositoryMock;
    }
    
    public function getMembershipMock($criterias)
    {
        if ($criterias['project']->getId() === 1) {
            return null;
        }
        return
            (new Membership())
            ->setProject($criterias['project'])
            ->setUser($criterias['user'])
            ->setCreatedAt(new \DateTime())
            ->setUpatedAt(new \DateTime())
            ->setIsActive(true)
        ;
    }
    
    public function getMembershipsMock(Project $project)
    {
        return [
            
            (new Membership())
            ->setProject($project)
            ->setCreatedAt(new \DateTime())
            ->setUpatedAt(new \DateTime())
            ->setIsActive(true),
            (new Membership())
            ->setProject($project)
            ->setCreatedAt(new \DateTime())
            ->setUpatedAt(new \DateTime())
            ->setIsActive(true)
        ];
    }
}