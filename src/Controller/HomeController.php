<?php

namespace App\Controller;

use App\Contracts\PostServiceInterface;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private PostServiceInterface $postService;

    public function __construct(PostServiceInterface $postService){
        $this->postService = $postService;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        $posts = $this->postService->getPublishedPostList();

        return $this->render('pages/home/index.html.twig', [
            'username' => $user ? $user->getUsername() : null,
            'role' => $user ? $user->getRoles()[0] : null,
            'posts' => $posts
        ]);
    }
    #[Route('/home/posts/{post}', name: 'app_home_post')]
    public function post(Post $post): Response
    {
        $user = $this->getUser();

        return $this->render('pages/home/post/show.html.twig', [
            'username' => $user ? $user->getUsername() : null,
            'role' => $user ? $user->getRoles()[0] : null,
            'post' => $post
        ]);
    }
}
