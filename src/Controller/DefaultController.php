<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BlogRepository;

class DefaultController extends AbstractController
{

//    #[Route('/default/{id}', name: 'blog_default', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
//    public function index(Request $request, int $id): Response
//    {
//        return $this->render('index.html.twig', [
//            'controller_name' => 'DefaultController',
//            'id' => $id,
//        ]);
//    }

    #[Route('/', name: 'blog_default')]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('default/index.html.twig', ['blogs' => $blogRepository->getBlogs()]);
    }

}
