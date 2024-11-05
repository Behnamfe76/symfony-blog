<?php

namespace App\Services;

use App\Contracts\PostServiceInterface;
use App\Entity\Approval;
use App\Entity\Post;
use App\Entity\User;
use App\Enums\PostStatusEnum;
use App\Repository\TemporaryUploadedFileRepository;
use App\Traits\UploadFileHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormInterface;

class PostService implements PostServiceInterface
{
    use UploadFileHandler;

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private TemporaryUploadedFileRepository $tempRepository;

    public function __construct(
        EntityManagerInterface          $entityManager,
        LoggerInterface                 $logger,
        TemporaryUploadedFileRepository $tempRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->tempRepository = $tempRepository;
    }

    public function storeTextualPost(
        Post                                       $post,
        FormInterface|\App\Form\CreatePostFormType $form,
        User                                       $user,
    ): Post|\Throwable|\Exception
    {
        try {
            $approval = new Approval();
            $approval->setChangedBy($user);
            $approval->setChangedTo(PostStatusEnum::PENDING); // Change 'getChangedTo' to 'setChangedTo'
            $approval->setPost($post);

            $post->setAuthor($user);
            $post->setPostType($form->get('postType')->getData());
            $post->setPostCategory($form->get('postCategory')->getData());
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());
            $post->setApproval($approval);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $post;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return $th;
        }
    }

    public function storeMediaPost(
        Post                                       $post,
        FormInterface|\App\Form\CreatePostFormType $form,
        User                                       $user,
        string                                     $projectDir,

    ): Post|\Throwable|\Exception
    {
        try {
            $this->entityManager->beginTransaction();
            $fileName = $form->get('attachment')->getData();
            $tempFile = $this->tempRepository->findByFileName($user, $fileName);
            $media = $this->moveTemporaryFile(
                $this->entityManager,
                $tempFile, $projectDir, $user, Post::class, 'posts'
            );
            if (!$media) {
                throw new \Exception("Media could not be created or file does not exist.");
            }

            $approval = new Approval();
            $approval->setChangedBy($user);
            $approval->setChangedTo(PostStatusEnum::PENDING); // Change 'getChangedTo' to 'setChangedTo'
            $approval->setPost($post);


            $post->setAuthor($user);
            $post->setPostType($form->get('postType')->getData());
            $post->setPostCategory($form->get('postCategory')->getData());
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());
            $post->setApproval($approval);
            $media->setPost($post);
            $post->addMedium($media);

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->entityManager->remove($tempFile);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $post;
        } catch (\Throwable $th) {
            $this->entityManager->rollback();

            $this->logger->error($th->getMessage());

            return $th;
        }
    }
}