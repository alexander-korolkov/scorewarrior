<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/item');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('ItemController', $client->getResponse()->getContent());
    }

    public function testGetItems()
    {
        $client = static::createClient();
        $client->request('GET', '/api/items');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
