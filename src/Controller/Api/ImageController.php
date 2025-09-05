<?php
namespace App\Controller\Api;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/images')]
class ImageController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function list(ImageRepository $repo, Request $request): JsonResponse
    {
        /*$session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }*/
        
        $images = $repo->findAll();
    $rows = array_map(function($i){ return ['id'=>$i->getId(),'url'=>$i->getUrl(),'x'=>$i->getX(),'y'=>$i->getY(),'addedBy'=>$i->getAddedBy(),'timestamp'=>$i->getTimestamp()->format(DATE_ATOM)]; }, $images);
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
        $image = new Image();
        $image->setUrl($data['url'] ?? '');
        $image->setX((int)($data['x'] ?? 0));
        $image->setY((int)($data['y'] ?? 0));
        $image->setAddedBy($data['addedBy'] ?? null);
        $image->setTimestamp(new \DateTime());
        $em->persist($image);
        $em->flush();
    return $this->json(['id'=>$image->getId(),'url'=>$image->getUrl(),'x'=>$image->getX(),'y'=>$image->getY(),'addedBy'=>$image->getAddedBy(),'timestamp'=>$image->getTimestamp()->format(DATE_ATOM)], 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Image $image, EntityManagerInterface $em, Request $request): JsonResponse
    {
        /*$session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }*/
        
        $em->remove($image);
        $em->flush();
        return $this->json(['ok' => true]);
    }

    #[Route('/{id}', methods: ['PUT','PATCH'])]
    public function update(Request $req, Image $image, EntityManagerInterface $em): JsonResponse
    {
        /*$session = $req->getSession();
        if (!$session || !$session->has('user_id')) {
            return $this->json(['error' => 'Non autorisé'], 401);
        }*/
        $data = json_decode($req->getContent(), true);
        if (isset($data['url'])) $image->setUrl($data['url']);
        if (isset($data['x'])) $image->setX((int)$data['x']);
        if (isset($data['y'])) $image->setY((int)$data['y']);
        if (isset($data['addedBy'])) $image->setAddedBy($data['addedBy']);
        $em->persist($image);
        $em->flush();
        return $this->json(['id'=>$image->getId(),'url'=>$image->getUrl(),'x'=>$image->getX(),'y'=>$image->getY(),'addedBy'=>$image->getAddedBy(),'timestamp'=>$image->getTimestamp()->format(DATE_ATOM)]);
    }
}
