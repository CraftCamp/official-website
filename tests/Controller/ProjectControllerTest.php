<?php

namespace Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

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
        $this->assertCount(2, $crawler->filter('.project'));
        $this->assertContains('Site officiel DevelopTech', $crawler->filter('.project:first-child')->text());
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
        $client->request('POST', '/projects', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
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
    
    public function testGetWorkspaceAction()
    {
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', '/projects/site-officiel-developtech/workspace');
        
        $this->assertStatusCode(200, $client);
        $this->assertContains('Fiche projet', $crawler->filter('#descriptions > header')->text());
    }
    
    public function testGetDetailsAction()
    {
        $client = $this->makeClient();
        $client->request('GET', '/projects/site-officiel-developtech/details');
        
        $this->assertStatusCode(302, $client);
        
        $client = $this->makeClient(true);
        $client->request('GET', '/projects/doctrine-backup-bundle/details');
        
        $this->assertStatusCode(403, $client);
        
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', '/projects/site-officiel-developtech/details');
        
        $this->assertStatusCode(200, $client);
        $this->assertContains('Sauvegarder', $crawler->filter('#project-details > footer')->text());
    }
    
    public function testPutDetailsAction()
    {
        $client = $this->makeClient();
        $client->request('PUT', '/projects/site-officiel-developtech/details', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'need_description' => 'I need a really great website !',
            'target_description' => 'The whole world !',
            'goal_description' => 'Have the first page on Google with every possible keyword'
        ]));
        
        $this->assertStatusCode(302, $client);
        
        $client = $this->makeClient(true);
        $client->request('PUT', '/projects/doctrine-backup-bundle/details', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'need_description' => 'I need a really great website !',
            'target_description' => 'The whole world !',
            'goal_description' => 'Have the first page on Google with every possible keyword'
        ]));
        
        $this->assertStatusCode(403, $client);
        
        $client = $this->makeClient(true);
        $client->request('PUT', '/projects/site-officiel-developtech/details', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'need_description' => 'I need a really great website !',
            'target_description' => 'The whole world !',
            'goal_description' => 'Have the first page on Google with every possible keyword'
        ]));
        
        $this->assertStatusCode(201, $client);
        
        $content = $client->getResponse()->getContent();
        
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertEquals(1, $data['project']['id']);
        $this->assertEquals($data['need_description'], 'I need a really great website !');
        $this->assertEquals($data['target_description'], 'The whole world !');
        $this->assertEquals($data['goal_description'], 'Have the first page on Google with every possible keyword');
    }
}
