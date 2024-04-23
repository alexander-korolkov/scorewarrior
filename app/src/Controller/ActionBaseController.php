<?php

namespace App\Controller;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Bundle\SecurityBundle\Security;

abstract class ActionBaseController extends AbstractController
{
    abstract protected function processAction();

    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security, string $className): JsonResponse
    {
        try {
            $action = $this->retrieveActionFromRequest($request, $serializer, $security, $className);

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

    public function retrieveActionFromRequest($request, $serializer, $security, $className)
    {
        $jsonContent = $request->getContent();
        $action = $serializer->deserialize($jsonContent, $className, 'json');

        // Set the creation time
        $currentTime = new \DateTimeImmutable();
        $action->setCreatedAt($currentTime);

        // Set status
        if ($security->isGranted('ROLE_EDITOR')) {
            $status = 'Approved';
        } else {
            $status = 'Pending';
        }

        $action->setStatus($status);

        // Getting user
        $user = $security->getUser();
        $action->setInitiator($user);

        return $action;
    }

    public function updateActionStatus(int $id, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, string $className): JsonResponse
    {
        // Retrieve the action entity by its ID
        $action = $entityManager->getRepository($className)->find($id);

        // Check if the action entity exists
        if (!$action) {
            // Return a 404 response if the action entity does not exist
            return $this->json(['message' => 'Action not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Decode the JSON request to get the new status
        $data = json_decode($request->getContent(), true);
        $newStatus = $data['status'] ?? null;

        if (in_array($newStatus, ['Approved', 'Rejected'])) {
            // Update the status of the ItemGrant entity
            $action->setStatus($newStatus);
            // Set the update time
            $currentTime = new \DateTimeImmutable();
            $action->setUpdatedAt($currentTime);

            $entityManager->flush();

            if ($newStatus === 'Approved') {
                $this->processAction();
            }

            // Return a successful response
            return $this->json($serializer->serialize($action, 'json'), JsonResponse::HTTP_OK, [], ['Content-Type' => 'application/json']);
        } else {
            // Return a response indicating that the provided status is invalid
            return $this->json(['message' => 'Invalid status provided.'], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function getPendingActions(EntityManagerInterface $entityManager, string $className): JsonResponse
    {
        $pendingActions = $entityManager->getRepository($className)->findBy(['status' => 'Pending']);

        return $this->json($pendingActions);
    }
}

