<?php
namespace App\Controller\Api;

use App\Entity\MapMarker;
use App\Repository\MapMarkerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/markers')]
class MapMarkerController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function list(MapMarkerRepository $repo, Request $request): JsonResponse
    {
        $session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $rows = array_map(function($m){ 
            return [
                'id'=>$m->getId(),
                'name'=>$m->getName(),
                'lat'=>$m->getLat(),
                'lng'=>$m->getLng(),
                'color'=>$m->getColor(),
            'description'=>$m->getDescription(),
            'timestamp'=>$m->getTimestamp()->format(DATE_ATOM)
        ]; 
    }, $repo->findAll());
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
        $m = new MapMarker();
        $m->setName($data['name'] ?? 'Point sans nom');
        $m->setLat((float)($data['lat'] ?? 0));
        $m->setLng((float)($data['lng'] ?? 0));
        $m->setColor($data['color'] ?? '#FF0000');
        $m->setDescription($data['description'] ?? null);
        $m->setTimestamp(new \DateTime());
        $em->persist($m);
        $em->flush();
    return $this->json([
        'id'=>$m->getId(),
        'name'=>$m->getName(),
        'lat'=>$m->getLat(),
        'lng'=>$m->getLng(),
        'color'=>$m->getColor(),
        'description'=>$m->getDescription(),
        'timestamp'=>$m->getTimestamp()->format(DATE_ATOM)
    ], 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(MapMarker $m, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $em->remove($m);
        $em->flush();
        return $this->json(['ok' => true]);
    }

    #[Route('/{id}', methods: ['PUT','PATCH'])]
    public function update(Request $req, MapMarker $m, EntityManagerInterface $em): JsonResponse
    {
        $session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $data = json_decode($req->getContent(), true);
        if (isset($data['name'])) $m->setName($data['name']);
        if (isset($data['lat'])) $m->setLat((float)$data['lat']);
        if (isset($data['lng'])) $m->setLng((float)$data['lng']);
        if (isset($data['color'])) $m->setColor($data['color']);
        if (isset($data['description'])) $m->setDescription($data['description']);
        $em->persist($m);
        $em->flush();
        return $this->json($m);
    }
}
