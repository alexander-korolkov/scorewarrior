<?php

namespace App\Controller;

use App\Entity\PlayerMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerMessageController extends ActionBaseController
{
    protected function processAction()
    {
        // TODO: Implement processAction() method. Invoke here everything that need to grant item.
    }

    #[Route('/api/player-messages', name: 'create_player_message', methods: ['POST'])]
    public function createPlayerMessage(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security): JsonResponse
    {

        return parent::createAction($request, $serializer, $validator, $entityManager, $security, PlayerMessage::class);
    }

    #[Route('/api/player-messages/{id}/status', name: 'update_player_message_status', methods: ['PATCH'])]
    public function updatePlayerMessageStatus(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        return parent::updateActionStatus($id, $request, $entityManager, $serializer, PlayerMessage::class);
    }

    #[Route('/api/player-messages/pending', name: 'player_messages_pending', methods: ['GET'])]
    public function getPendingPlayerMessages(EntityManagerInterface $entityManager): JsonResponse
    {

        return parent::getPendingActions($entityManager, PlayerMessage::class);
    }
}

