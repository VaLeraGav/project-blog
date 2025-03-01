<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\Blog;
use App\Filter\BlogFilter;
use App\Form\BlogFilterType;
use App\Form\BlogType;
use App\Message\ContentWatchJob;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user/blog')]
final class BlogController extends AbstractController
{
    #[Route(name: 'app_user_blog_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, BlogRepository $blogRepository): Response
    {
        $blogFilter = new BlogFilter($this->getUser());

        $form = $this->createForm(BlogFilterType::class, $blogFilter);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $blogRepository->findByBlogFilter($blogFilter),
            $request->query->getInt('offset', 1),
            10
        );

        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination,
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/new', name: 'app_user_blog_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
    ): Response {
        $blog = new Blog($this->getUser());
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blog);
            $entityManager->flush();

            //  $blog->setPercent(
            //      $contentWatchApi->checkText($blog->getText())
            //  );
            //  $entityManager->flush();

            $bus->dispatch(new ContentWatchJob($blog->getId()));

            return $this->redirectToRoute('app_user_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        //  $this->addFlash(
        //       'notice',
        //       'Your changes were saved!'
        //   );

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_blog_show', methods: ['GET'])]
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    #[IsGranted('edit', 'blog', 'Blog not found', 404)]
    #[Route('/{id}/edit', name: 'app_user_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    // для delete  нужно обновить
    #[IsGranted('edit', 'blog', 'Blog not found', 404)]
    #[Route('/{id}', name: 'app_user_blog_delete_post', methods: ['POST'])]
    public function deletePost(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_blog_index', [], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('edit', 'blog', 'Blog not found', 404)]
    #[Route('/{id}/delete', name: 'app_user_blog_delete', methods: ['GET'])]
    public function delete(Blog $blog, EntityManagerInterface $em): Response
    {
        $em->remove($blog);
        $em->flush();

        return $this->redirectToRoute('app_user_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
