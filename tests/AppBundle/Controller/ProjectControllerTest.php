<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

use AppBundle\Entity\User\ProductOwner;
use AppBundle\Entity\{
    Project,
    Organization
};

class ProjectControllerTest extends WebTestCase {
    public function testGetListAction() {
        $client = static::createClient();
        $client->getContainer()->set('developtech_agility.project_manager', $this->getProjectManagerMock());
        $crawler = $client->request('GET', '/projects');


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(2, $crawler->filter('.list-group li'));
    }

    public function testNewAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/projects/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Création de votre projet', $crawler->filter('h3')->text());

        $form = $crawler->selectButton('Valider la création')->form([
            'project[name]' => 'Chez Gégé',
            'project[productOwner][username]' => 'gégé',
            'project[productOwner][email]' => 'gege@gmail.com',
            'project[productOwner][plainPassword][first]' => '123soleil',
            'project[productOwner][plainPassword][second]' => '123soleil',
            'project[productOwner][organization][name]' => 'Gégé SARL',
            'project[productOwner][organization][description]' => 'Le restaurant de Gégé'
        ]);

        $client->getContainer()->set('developtech_agility.project_manager', $this->getProjectManagerMock());
        $client->getContainer()->set('developtech.organization_manager', $this->getProjectManagerMock());
        $client->getContainer()->set('developtech.user_manager', $this->getProjectManagerMock());

        $crawler = $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Chez Gégé', $crawler->filter('.list-group')->text());
    }

    public function getOrganizationManagerMock() {
        $organizationManagerMock = $this
            ->getMockBuilder('AppBundle\Manager\OrganizationManager')
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
    public function createOrganizationMock(Organization &$organization) {
        $organization
            ->setId(1)
            ->setSlug()
        ;
    }

    public function getProjectManagerMock() {
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

    public function getProjectMock() {
        return
            (new Project())
            ->setId(1)
            ->setName('Free chicken')
            ->setSlug('free-chicken')
            ->setBetaTestStatus('closed')
            ->setNbBetaTesters(12)
            ->setProductOwner((new ProductOwner()))
            ->setCreatedAt(new \DateTime())
        ;
    }

    public function getProjectsMock() {
        return [
            (new Project())
            ->setId(1)
            ->setName('Free chicken')
            ->setSlug('free-chicken')
            ->setBetaTestStatus('open')
            ->setNbBetaTesters(12)
            ->setProductOwner((new ProductOwner()))
            ->setCreatedAt(new \DateTime()),
            (new Project())
            ->setId(2)
            ->setName('Free bacon')
            ->setSlug('free-bacon')
            ->setBetaTestStatus('closed')
            ->setNbBetaTesters(0)
            ->setProductOwner((new ProductOwner()))
            ->setCreatedAt(new \DateTime())
        ];
    }

    public function getUserManagerMock() {
        $userManagerMock = $this
            ->getMockBuilder('AppBundle\Manager\UserManager')
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
