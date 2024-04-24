<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

class UserControllerTest extends WebTestCase
{
    public function testCreateUserWithValidData()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users', [
            'content' => json_encode([
                'username' => 'newuser',
                'email' => 'newuser@example.com',
                'password' => 'qwe123',
                'roles' => 'ROLE_USER',
                'avatar' => '/avatar.jpg'
            ]),
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('User created successfully.', $responseContent['message']);
    }

    public function testCreateUserWithInvalidData()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users', [
            'content' => json_encode([
                'username' => '',
                'email' => 'invalidemail',
                'password' => '',
                'roles' => 'ROLE_USER',
                'avatar' => ''
            ]),
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateUserAccessDenied()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users', [
            'content' => json_encode([
                'username' => 'newuser',
                'email' => 'newuser@example.com',
                'password' => 'securepassword',
                'roles' => 'ROLE_USER',
                'avatar' => 'path/to/avatar.jpg'
            ]),
            'headers' => ['Content-Type' => 'application/json']
        ]);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Access Denied.', $responseContent['message']);
    }

    public function testGetAllUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}
