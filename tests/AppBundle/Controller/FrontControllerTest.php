<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase {
    public function testHomepageAction() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('DevelopTech', $crawler->filter('.navbar-brand')->text());
    }
}
