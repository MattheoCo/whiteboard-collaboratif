<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/events')]
class CalendarController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function list(EventRepository $repo, Request $request): JsonResponse
    {
        // Temporarily disable auth for testing
        $events = $repo->findAll();
        $rows = array_map(function($e) {
            return [
                'id' => $e->getId(),
                'title' => $e->getTitle(),
                'start' => $e->getStart()->format(DATE_ATOM),
                'end' => $e->getEnd() ? $e->getEnd()->format(DATE_ATOM) : null,
                'color' => $e->getColor(),
                'description' => $e->getDescription(),
                'isAllDay' => $e->getIsAllDay()
            ];
        }, $events);
        return $this->json($rows);
    }

    #[Route('/', methods: ['POST'])]
    public function create(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $data = json_decode($req->getContent(), true);
        
        $event = new Event();
        $event->setTitle($data['title'] ?? 'Événement sans titre');
        $event->setStart(new \DateTime($data['start'] ?? 'now'));
        if (!empty($data['end'])) {
            $event->setEnd(new \DateTime($data['end']));
        }
        $event->setColor($data['color'] ?? '#3498db');
        $event->setDescription($data['description'] ?? null);
        $event->setIsAllDay($data['isAllDay'] ?? false);
        
        $em->persist($event);
        $em->flush();
        
        return $this->json([
            'id' => $event->getId(),
            'title' => $event->getTitle(),
            'start' => $event->getStart()->format(DATE_ATOM),
            'end' => $event->getEnd() ? $event->getEnd()->format(DATE_ATOM) : null,
            'color' => $event->getColor(),
            'description' => $event->getDescription(),
            'isAllDay' => $event->getIsAllDay()
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $req, Event $event, EntityManagerInterface $em): JsonResponse
    {
        $session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $data = json_decode($req->getContent(), true);
        
        if (isset($data['title'])) $event->setTitle($data['title']);
        if (isset($data['start'])) $event->setStart(new \DateTime($data['start']));
        if (isset($data['end'])) {
            $event->setEnd(!empty($data['end']) ? new \DateTime($data['end']) : null);
        }
        if (isset($data['color'])) $event->setColor($data['color']);
        if (isset($data['description'])) $event->setDescription($data['description']);
        if (isset($data['isAllDay'])) $event->setIsAllDay($data['isAllDay']);
        
        $em->persist($event);
        $em->flush();
        
        return $this->json([
            'id' => $event->getId(),
            'title' => $event->getTitle(),
            'start' => $event->getStart()->format(DATE_ATOM),
            'end' => $event->getEnd() ? $event->getEnd()->format(DATE_ATOM) : null,
            'color' => $event->getColor(),
            'description' => $event->getDescription(),
            'isAllDay' => $event->getIsAllDay()
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Event $event, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $em->remove($event);
        $em->flush();
        return $this->json(['ok' => true]);
    }
}
