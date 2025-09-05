<?php
namespace App\Controller\Api;

use App\Entity\StickyNote;
use App\Repository\StickyNoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/stickynotes')]
class StickyNoteController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function list(StickyNoteRepository $repo, Request $request): JsonResponse
    {
        /*$session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }*/
        
        $rows = array_map(function($n){
            return [
                'id' => $n->getId(),
                'text' => $n->getText(),
                'x' => $n->getX(),
                'y' => $n->getY(),
                'color' => $n->getColor(),
                'done' => $n->isDone(),
                'timestamp' => $n->getTimestamp()->format(DATE_ATOM)
            ];
        }, $repo->findAll());
        return $this->json($rows);
    }

    #[Route('/', methods: ['POST'])]
    public function create(Request $req, EntityManagerInterface $em): JsonResponse
    {
        /*$session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }*/
        
        $data = json_decode($req->getContent(), true);
        $note = new StickyNote();
        $note->setText($data['text'] ?? null);
        $note->setX((int)($data['x'] ?? 0));
        $note->setY((int)($data['y'] ?? 0));
        $note->setColor($data['color'] ?? null);
        $note->setDone((bool)($data['done'] ?? false));
        $note->setTimestamp(new \DateTime());
        $em->persist($note);
        $em->flush();
        return $this->json([
            'id' => $note->getId(),
            'text' => $note->getText(),
            'x' => $note->getX(),
            'y' => $note->getY(),
            'color' => $note->getColor(),
            'done' => $note->isDone(),
            'timestamp' => $note->getTimestamp()->format(DATE_ATOM)
        ], 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(StickyNote $note, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $em->remove($note);
        $em->flush();
        return $this->json(['ok' => true]);
    }

    #[Route('/{id}', methods: ['PUT','PATCH'])]
    public function update(Request $req, StickyNote $note, EntityManagerInterface $em): JsonResponse
    {
        $session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $data = json_decode($req->getContent(), true);
        if (isset($data['text'])) $note->setText($data['text']);
        if (isset($data['x'])) $note->setX((int)$data['x']);
        if (isset($data['y'])) $note->setY((int)$data['y']);
        if (isset($data['color'])) $note->setColor($data['color']);
        if (isset($data['done'])) $note->setDone((bool)$data['done']);
        $em->persist($note);
        $em->flush();
        return $this->json($note);
    }
}
