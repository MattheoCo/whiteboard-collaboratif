<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoardControllerNew extends AbstractController
{
	#[Route('/_board_new', name: 'app_board_new', methods: ['GET'])]
	public function index(): Response
	{
		return new Response('BoardControllerNew present');
	}
}

