<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogsController extends AbstractController
{
	#[Route('/_logs', name: 'app_logs', methods: ['GET'])]
	public function index(): Response
	{
		return new Response('LogsController present');
	}
}
