<?php

namespace App\Controller;

use App\Entity\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ItemGrant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class ItemGrantController extends ActionBaseController
{
    private function getItems($request)
    {
        $data = json_decode($request->getContent(), true);

        return $data['items'] ?? [];
    }

    protected function processAction()
    {
        // TODO: Implement processAction() method. Invoke here everything that need to grant item.
    }

    #[Route('/api/item-grants', name: 'create_item_grant', methods: ['POST'])]
    public function createItemGrant(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security): JsonResponse
    {
        try {
            $action = $this->retrieveActionFromRequest($request, $serializer, $security, ItemGrant::class);
            $items = $this->getItems($request);

            if (!empty($items)) {
                foreach ($items as $itemId) {
                    $item = $entityManager->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $action->addItem($item);
                    }
                }
            }

            // Validate the deserialized object
            $errors = $validator->validate($action);
            if (count($errors) > 0) {
                return $this->json($errors, JsonResponse::HTTP_BAD_REQUEST);
            }

            $entityManager->persist($action);
            $entityManager->flush();

            if ($security->isGranted('ROLE_EDITOR')) {
                $this->processAction();
            }

            return $this->json($action, JsonResponse::HTTP_CREATED);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid JSON'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

    }

    #[Route('/api/item-grants/{id}/status', name: 'update_item_grant_status', methods: ['PATCH'])]
    public function updateItemGrantStatus(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {

        return parent::updateActionStatus($id, $request, $entityManager, $serializer, ItemGrant::class);
    }

    #[Route('/api/item-grants/pending', name: 'item_grants_pending', methods: ['GET'])]
    public function getPendingItemGrants(EntityManagerInterface $entityManager): JsonResponse
    {

        return parent::getPendingActions($entityManager, ItemGrant::class);
    }
}

