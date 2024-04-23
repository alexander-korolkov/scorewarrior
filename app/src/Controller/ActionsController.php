<?php

namespace App\Controller;

use App\Entity\ItemGrant;
use App\Entity\PlayerMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ActionsController extends AbstractController
{
    #[Route('/api/actions/count', name: 'actions_count', methods: ['GET'])]
    public function getPendingActionsCount(EntityManagerInterface $entityManager): JsonResponse
    {
        $itemGrantsCount = $entityManager->getRepository(ItemGrant::class)->count(['status' => 'Pending']);
        $playerMessagesCount = $entityManager->getRepository(PlayerMessage::class)->count(['status' => 'Pending']);

        return $this->json([
            'itemGrantsCount' => $itemGrantsCount,
            'playerMessagesCount' => $playerMessagesCount,
        ]);
    }

    #[Route('/api/actions/log', name: 'actions_sorted', methods: ['GET'])]
    public function getActionsLog(EntityManagerInterface $entityManager): JsonResponse
    {
        $itemGrants = $entityManager->getRepository(ItemGrant::class)->findBy([
            'status' => ['Rejected', 'Approved']
        ]);

        $playerMessages = $entityManager->getRepository(PlayerMessage::class)->findBy([
            'status' => ['Rejected', 'Approved']
        ]);

        $allActions = array_merge($itemGrants, $playerMessages);

        // sorting
        usort($allActions, function ($a, $b) {
            return $a->getCreatedAt() <=> $b->getCreatedAt();
        });

        return $this->json($allActions);
    }

    #[Route('/api/user-actions', name: 'actions_by_user', methods: ['GET'])]
    public function getActionsByUser(EntityManagerInterface $entityManager, Security $security): JsonResponse
    {
        $user = $security->getUser();
        if ($user) {
            $userId = $user->getId();
        } else {
            return $this->json(['message' => 'Invalid user.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $itemGrants = $entityManager->getRepository(ItemGrant::class)->findBy([
            'initiator' => [$userId]
        ]);

        $playerMessages = $entityManager->getRepository(PlayerMessage::class)->findBy([
            'initiator' => [$userId]
        ]);

        $allActions = array_merge($itemGrants, $playerMessages);

        // sorting
        usort($allActions, function ($a, $b) {
            return $a->getCreatedAt() <=> $b->getCreatedAt();
        });

        return $this->json($allActions);
    }
}
