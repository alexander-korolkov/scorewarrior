<?php

namespace App\Controller;

use App\Entity\Item;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    #[Route('/api/items', name: 'api_items', methods: ['GET'])]
    public function getItems(ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $items = $doctrine->getRepository(Item::class)->findAll();
        $json = $serializer->serialize($items, 'json');

        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }
}
