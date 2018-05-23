<?php

namespace Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

use App\Entity\User\Member;

use App\DataFixtures\ORM\LoadOrganizationData;
use App\DataFixtures\ORM\Community\LoadCommunityData;
use App\DataFixtures\ORM\User\LoadActivationLinkData;
use App\DataFixtures\ORM\User\LoadProductOwnerData;

class MemberControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures([
            LoadActivationLinkData::class,
            LoadOrganizationData::class,
            LoadProductOwnerData::class,
            LoadCommunityData::class,
        ]);
    }
    
    public function testDashboardAction()
    {
        $client = $this->makeClient(true);
        
        $crawler = $client->request('GET', '/members/dashboard');
        
        $this->assertStatusCode(200, $client);
        $this->assertContains('Mes communautés', $crawler->filter('#communities > header > h3')->text());
    }
    
    public function testDashboardActionWithAnonymousToken()
    {
        $client = $this->makeClient();
        
        $client->request('GET', '/members/dashboard');
        
        $this->assertStatusCode(302, $client);
    }
    
    public function testNewAction()
    {
        $client = $this->makeClient(true);
        
        $crawler = $client->request('GET', '/members/new');
        
        $this->assertContains('Rejoindre la communauté', $crawler->filter('#registration > header > h3')->text());
    }
    
    public function testCreateAction()
    {
        $client = $this->makeClient(true);
        $client->request('POST', '/members', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'Mickael',
            'email' => 'mickael@example.org',
            'password' => 'test',
            'password_confirmation' => 'test'
        ]));
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $content = $client->getResponse()->getContent();
        
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertEquals(3, $data['id']);
        $this->assertEquals('Mickael', $data['username']);
        $this->assertFalse($data['is_enabled']);
        $this->assertEquals(Member::TYPE_MEMBER, $data['type']);
    }
    
    public function testCreateActionWithWrongPassword()
    {
        $client = $this->makeClient(true);
        $client->request('POST', '/members', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'Mickael',
            'email' => 'mickael@example.org',
            'password' => 'test',
            'password_confirmation' => 'wrong'
        ]));
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        
        $content = $client->getResponse()->getContent();
        
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertEquals('users.password_mismatch', $data['message']);
    }
}