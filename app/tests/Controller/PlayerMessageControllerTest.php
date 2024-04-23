<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerMessageControllerTest extends WebTestCase
{
    public function testCreatePlayerMessage(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'editor',
                'password' => 'qwe123',
            ])
        );

        $responseContent = $client->getResponse()->getContent();

        $responseData = json_decode($responseContent, true);

        $token = $responseData['token'];

        $client->request('POST', '/api/player-messages', [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            ],
            json_encode([
                'playerId' => 1,
                'message' => 'Test message',
                'status' => 'Pending',
            ])
        );

        $this->assertResponseIsSuccessful();
        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals('Test message', $responseData['message']);
    }
}
