<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
	#[Route('/_test', name: 'app_test', methods: ['GET'])]
	public function index(): Response
	{
		return new Response('TestController present');
	}
}

