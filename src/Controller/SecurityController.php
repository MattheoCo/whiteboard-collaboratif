<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
	#[Route('/_security', name: 'app_security', methods: ['GET'])]
	public function index(): Response
	{
		return new Response('SecurityController present');
	}
}

