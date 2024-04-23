<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(UserInterface $user = null): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'User not found or invalid credentials',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Test token response
        return $this->json([
            'user'  => $user->getUsername(),
            // I will see this only if something wrong
            'token' => 'fake_jwt_token_here'
        ]);
    }
}
