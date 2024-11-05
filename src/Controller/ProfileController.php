<?php

namespace App\Controller;

use App\Contracts\PostServiceInterface;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    private PostServiceInterface $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('/pages/profile/index.html.twig', [
            'page' => '/profile',
            'user' => [
                'username' => $this->getUser()->getUsername(),
                'email' => $this->getUser()->getEmail(),
            ],
        ]);
    }

    #[Route('/profile/posts', name: 'profile_post_list')]
    public function posts(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $posts = $this->postService->userPosts($this->getUser());

        return $this->render('/pages/profile/posts.html.twig', [
            'page' => '/profile/posts',
            'posts' => $posts,
        ]);
    }
}
