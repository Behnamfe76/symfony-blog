<?php

namespace App\Services;

use App\Contracts\PostServiceInterface;
use App\Entity\Approval;
use App\Entity\Post;
use App\Entity\User;
use App\Enums\PostStatusEnum;
use App\Repository\PostRepository;
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

    private PostRepository $postRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        TemporaryUploadedFileRepository $tempRepository,
        PostRepository $postRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->tempRepository = $tempRepository;
        $this->postRepository = $postRepository;
    }

    public function storeTextualPost(
        Post $post,
        FormInterface|\App\Form\CreatePostFormType $form,
        User $user,
    ): Post|\Throwable|\Exception {
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
        Post $post,
        FormInterface|\App\Form\CreatePostFormType $form,
        User $user,
        string $projectDir,
    ): Post|\Throwable|\Exception {
        try {
            $this->entityManager->beginTransaction();
            $fileName = $form->get('attachment')->getData();
            $tempFile = $this->tempRepository->findByFileName($user, $fileName);
            $media = $this->moveTemporaryFile(
                $this->entityManager,
                $tempFile, $projectDir, $user, Post::class, 'posts'
            );
            if (!$media) {
                throw new \Exception('Media could not be created or file does not exist.');
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

    public function userPosts(User $user): \Doctrine\Common\Collections\Collection
    {
        return $user->getPosts();
    }

    public function getUnpublishedPostList(int $page = 1, int $perPage = 10): \Throwable|\Exception|array
    {
        try {
            return $this->postRepository->getUnpublishedPostList($page, $perPage);
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());

            return $th;
        }
    }

    public function getAllPostList(int $page = 1, int $perPage = 10): \Throwable|\Exception|array
    {
        try {
            return $this->postRepository->getAllPostList($page, $perPage);
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());

            return $th;
        }
    }
    public function getPublishedPostList()
    {
        try {
            return $this->postRepository->getPublishedPostList();
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());

            return $th;
        }
    }

    public function updatePostStatus(Post $post, PostStatusEnum $status, User $user)
    {
        try {
            $this->entityManager->beginTransaction();
            $approval = $post->getApproval();
            $approval->setUpdatedAt(new \DateTimeImmutable());
            $approval->setChangedBy($user);
            $approval->setChangedTo($status);
            $post->setUpdatedAt(new \DateTimeImmutable());

            if (PostStatusEnum::PUBLISHED === $status) {
                $approval->setApprovedAt(new \DateTimeImmutable());
                $post->setPublishedAt(new \DateTimeImmutable());
            }
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
