<?php

namespace App\Controller;

use App\Contracts\PostServiceInterface;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    private PostServiceInterface $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $posts = $this->postService->getUnpublishedPostList($page, $limit);

        return $this->render('pages/admin/index.html.twig', [
            'page' => '/admin',
            'posts' => $posts['data'],
            'total' => $posts['total'],
            'pageNumber' => $page,
            'limit' => $limit,
        ]);
    }

    #[Route('/admin/posts', name: 'app_admin_posts')]
    public function posts(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $posts = $this->postService->getAllPostList($page, $limit);

        return $this->render('pages/admin/posts.html.twig', [
            'page' => '/admin/posts',
            'posts' => $posts['data'],
            'total' => $posts['total'],
            'pageNumber' => $page,
            'limit' => $limit,
        ]);
    }


    #[Route('/admin/posts/{post}', name: 'app_admin_posts_show')]
    public function showPost(Post $post): Response
    {
        return $this->render('pages/admin/show.html.twig', [
            'page' => '/admin/posts',
            'post' => $post,
        ]);
    }
}
