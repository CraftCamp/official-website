<?php

namespace Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

use App\Entity\User\ProductOwner;
use Developtech\AgilityBundle\Entity\Project;
use Developtech\AgilityBundle\Entity\BetaTest;
use App\Entity\Organization;

use App\DataFixtures\ORM\User\LoadActivationLinkData;
use App\DataFixtures\ORM\User\LoadProductOwnerData;
use App\DataFixtures\ORM\LoadOrganizationData;
use App\DataFixtures\ORM\LoadProjectData;

class ProjectControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures([
            LoadActivationLinkData::class,
            LoadOrganizationData::class,
            LoadProductOwnerData::class,
            LoadProjectData::class
        ]);
    }
    
    public function testGetListAction()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/projects');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('.project'));
        $this->assertContains('Site officiel DevelopTech', $crawler->filter('.project')->text());
    }

    public function testNewAction()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/projects/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Parlez-nous de votre projet', $crawler->filter('.fs-title')->text());
    }
    
    public function testCreateAction()
    {
        $client = $this->makeClient();
        $client->request('POST', '/projects', [], [], [], json_encode([
            'project' => [
                'name' => 'Chez Gégé',
                'description' => 'Un bô restaurant !'
            ],
            'product_owner' => [
                'username' => 'gégé',
                'email' => 'gege@gmail.com',
                'password' => '123soleil',
                'password_confirmation' => '123soleil',
            ],
            'organization' => [
                'name' => 'Gégé SARL',
                'type' => 'small_company',
                'description' => 'Le restaurant de Gégé'
            ]
        ]));
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function getOrganizationManagerMock()
    {
        $organizationManagerMock = $this
            ->getMockBuilder('App\Manager\OrganizationManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $organizationManagerMock
            ->expects($this->any())
            ->method('createOrganization')
            ->willReturnCallback([$this, 'createOrganizationMock'])
        ;
        return $organizationManagerMock;
    }

    /**
     * @param Organization &$organization
     */
    public function createOrganizationMock(Organization &$organization)
    {
        $organization
            ->setId(1)
            ->setSlug()
        ;
    }

    public function getProjectManagerMock()
    {
        $projectManagerMock = $this
            ->getMockBuilder('Developtech\AgilityBundle\Manager\ProjectManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $projectManagerMock
            ->expects($this->any())
            ->method('createProject')
            ->willReturnCallback([$this, 'getProjectMock'])
        ;
        $projectManagerMock
            ->expects($this->any())
            ->method('getProjects')
            ->willReturnCallback([$this, 'getProjectsMock'])
        ;
        return $projectManagerMock;
    }

    public function getProjectMock()
    {
        return
            (new Project())
            ->setId(1)
            ->setName('Free chicken')
            ->setSlug('free-chicken')
            ->addBetaTest(
                (new BetaTest())
                ->setId(1)
                ->setName('First Beta !')
                ->setSlug('first-beta')
            )
            ->setProductOwner((new ProductOwner()))
            ->setCreatedAt(new \DateTime())
        ;
    }

    public function getProjectsMock()
    {
        return [
            (new Project())
            ->setId(1)
            ->setName('Free chicken')
            ->setSlug('free-chicken')
            ->setProductOwner((new ProductOwner()))
            ->setCreatedAt(new \DateTime()),
            (new Project())
            ->setId(2)
            ->setName('Free bacon')
            ->setSlug('free-bacon')
            ->setProductOwner((new ProductOwner()))
            ->setCreatedAt(new \DateTime())
        ];
    }

    public function getUserManagerMock()
    {
        $userManagerMock = $this
            ->getMockBuilder('App\Manager\UserManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userManagerMock
            ->expects($this->any())
            ->method('createUser')
            ->willReturn(true)
        ;
        return $userManagerMock;
    }
}
