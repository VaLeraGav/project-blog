<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/default/{id}', name: 'blog_default', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    public function index(Request $request, int $id): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'DefaultController',
            'id' => $id,
        ]);
    }
}
