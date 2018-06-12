<?php

namespace Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    public function testHomepageAction()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Projets', $crawler->filter('body > nav')->text());
    }
}
