<?php

namespace App\Controller;

use App\Contracts\PostServiceInterface;
use App\Entity\Post;
use App\Form\CreatePostFormType;
use App\Message\DeleteTemporaryFiles;
use App\Traits\UploadFileHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    use UploadFileHandler;

    private LoggerInterface $logger;
    private PostServiceInterface $postService;

    public function __construct(LoggerInterface $logger, PostServiceInterface $postService)
    {
        $this->logger = $logger;
        $this->postService = $postService;
    }

    #[Route('/post/create', name: 'app_post_create')]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $post = new Post();
        $form = $this->createForm(CreatePostFormType::class, $post);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedPostType = $form->get('postType')->getData();

            if ($submittedPostType->getName() === "متن") {
                $result = $this->postService->storeTextualPost(
                    $post,
                    $form,
                    $user
                );

            } else {
                $result = $this->postService->storeMediaPost(
                    $post,
                    $form,
                    $user,
                    $this->getParameter('kernel.project_dir')
                );
            }
            if($result instanceof \Throwable){
                $this->logger->error($result->getMessage());
                dd($result->getMessage());
                $this->addFlash('error', 'خطای سرور');
                return $this->redirectToRoute('app_post_create');
            }
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('/pages/post/create.html.twig', [
            'page' => '/post/create',
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/upload', name: 'app_post_upload', methods: ['POST'])]
    public function uploadFile(
        Request                $request,
        EntityManagerInterface $entityManager,
        MessageBusInterface    $bus,
    ): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $file = $request->files->get('post_attachment');
        $projectDir = $this->getParameter('kernel.project_dir');

        $tempFile = $this->uploadTemporaryFile(
            $file,
            $projectDir,
            $entityManager,
            $this->getUser(),
        );

        if ($tempFile instanceof \Throwable) {
            $this->logger->error($tempFile->getMessage());

            return new Response('Failed to upload file', Response::HTTP_BAD_REQUEST);
        }

        $bus->dispatch(new DeleteTemporaryFiles($tempFile, $projectDir), [new DelayStamp(900000)]);

        return new Response('File uploaded successfully!', Response::HTTP_OK);
    }
}