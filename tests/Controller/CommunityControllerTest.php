<?php

namespace Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\DataFixtures\ORM\LoadOrganizationData;
use App\DataFixtures\ORM\Community\LoadCommunityData;
use App\DataFixtures\ORM\User\LoadActivationLinkData;
use App\DataFixtures\ORM\User\LoadProductOwnerData;

class CommunityControllerTest extends WebTestCase
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
    
    public function testNewAction()
    {
        $client = $this->makeClient(true);
        
        $crawler = $client->request('GET', '/communities/new');
        
        $this->assertContains('Créer une communauté', $crawler->filter('#creation-form > header > h3')->text());
    }
    
    public function testCreateAction()
    {
        $client = $this->makeClient(true);
        copy('./public/images/uploads/community-test-picture.jpeg', './public/images/uploads/community-test-picture-copy.jpeg');
        $client->request('POST', '/communities', [
            'name' => 'Test'
        ], [
            'picture' => new UploadedFile(
                './public/images/uploads/community-test-picture-copy.jpeg',
                'community-test-picture-copy.jpeg',
                'image/jpeg'
            )
        ]);
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $content = $client->getResponse()->getContent();
        
        $this->assertJson($content);
        
        $data = json_decode($content, true);
        
        $this->assertEquals(6, $data['id']);
        $this->assertEquals('Test', $data['name']);
        $this->assertEquals('test', $data['slug']);
        unlink('./public/images/uploads/community-test.jpeg');
    }
    
    public function testGetAllAction()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/communities');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Symfony', $crawler->filter('.community')->text());
    }
}