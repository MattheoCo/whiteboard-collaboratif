<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class BoardController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if ($session && $session->has('user_id')) {
            return new RedirectResponse('/board');
        }
        return new RedirectResponse('/login');
    }

    #[Route('/board', name: 'app_board')]
    public function board(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session || !$session->has('user_id')) {
            return new RedirectResponse('/login');
        }
        
        return $this->render('board-final.html.twig');
    }
}
