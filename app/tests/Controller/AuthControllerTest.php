<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'editor',
                'password' => 'qwe123',
            ])
        );

        $this->assertResponseStatusCodeSame(200);
        $responseContent = $client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('token', $responseData);
    }

    public function testLoginFail(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'wronguser',
                'password' => 'wrongpass',
            ])
        );

        $this->assertResponseStatusCodeSame(401); // Проверьте, какой статус-код возвращает ваш API при ошибке аутентификации
    }
}
