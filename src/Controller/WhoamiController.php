<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WhoamiController extends AbstractController
{
	#[Route('/_whoami', name: 'app_whoami', methods: ['GET'])]
	public function index(): Response
	{
		return new Response('WhoamiController present');
	}
}

