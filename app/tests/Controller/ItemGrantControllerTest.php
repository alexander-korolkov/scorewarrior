<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Item;
use App\Entity\ItemGrant;

class ItemGrantControllerTest extends WebTestCase
{
    public function testCreateItemGrantWithValidData()
    {
        $client = static::createClient();
        $client->request('POST', '/api/item-grants', [
            'content' => json_encode(['items' => [1, 2, 3]]),
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateItemGrantWithInvalidData()
    {
        $client = static::createClient();
        $client->request('POST', '/api/item-grants', [
            'content' => 'not a json',
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid JSON', $responseContent['message']);
    }

    public function testUpdateItemGrantStatus()
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/item-grants/1/status', [
            'content' => json_encode(['status' => 'Approved']),
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetPendingItemGrants()
    {
        $client = static::createClient();
        $client->request('GET', '/api/item-grants/pending');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
