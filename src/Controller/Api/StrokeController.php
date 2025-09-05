<?php
namespace App\Controller\Api;

use App\Entity\Stroke;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/strokes')]
class StrokeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em){}

    #[Route('/', name: 'api_strokes_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $items = $this->em->getRepository(Stroke::class)->findBy([], ['createdAt' => 'ASC']);
    $out = array_map(fn(Stroke $s)=>['id'=>$s->getId(),'data'=>$s->getData(),'vector'=>$s->getVector(),'createdAt'=>$s->getCreatedAt()->format(DATE_ATOM)], $items);
        return $this->json($out);
    }

    #[Route('/', name: 'api_strokes_create', methods: ['POST'])]
    public function create(Request $req): JsonResponse
    {
        $session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }
        
        $body = json_decode($req->getContent(), true);
    $data = $body['data'] ?? null;
    $vector = $body['vector'] ?? null;
    if(!$data && !$vector) return $this->json(['error'=>'no data'], 400);
    $s = new Stroke($data, $vector);
        $this->em->persist($s);
        $this->em->flush();
    return $this->json(['id'=>$s->getId(),'data'=>$s->getData(),'vector'=>$s->getVector(),'createdAt'=>$s->getCreatedAt()->format(DATE_ATOM)], 201);
    }
}
