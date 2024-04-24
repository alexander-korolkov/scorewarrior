<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\ItemGrant;
use App\Entity\PlayerMessage;

class ActionsControllerTest extends WebTestCase
{
    public function testGetPendingActionsCount()
    {
        $client = static::createClient();
        $client->request('GET', '/api/actions/count');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('itemGrantsCount', $responseContent);
        $this->assertArrayHasKey('playerMessagesCount', $responseContent);
    }

    public function testGetActionsLog()
    {
        $client = static::createClient();
        $client->request('GET', '/api/actions/log');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseContent);
        foreach ($responseContent as $action) {
            $this->assertArrayHasKey('createdAt', $action);
        }
    }

    public function testGetActionsByUserWithUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/user-actions');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseContent);
    }

    public function testGetActionsByUserWithoutUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/user-actions');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid user.', $responseContent['message']);
    }
}
