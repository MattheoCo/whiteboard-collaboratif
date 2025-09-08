<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DebugAuthController extends AbstractController
{
	#[Route('/_debug_auth', name: 'debug_auth', methods: ['GET'])]
	public function index(): Response
	{
		// Minimal controller used for debugging/auth helper routes.
		return new Response('DebugAuthController is present');
	}
}
