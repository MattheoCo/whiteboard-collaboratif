<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/events')]
class EventController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json(['message' => 'Events API working']);
    }
}
