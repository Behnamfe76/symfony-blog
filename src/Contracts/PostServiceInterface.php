<?php

namespace App\Contracts;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostFormType;
use Symfony\Component\Form\FormInterface;

interface PostServiceInterface
{
    public function storeTextualPost(Post $post, CreatePostFormType $form, User $user);

    public function storeMediaPost(
        Post $post,
        FormInterface|CreatePostFormType $form,
        User $user,
        string $projectDir,
    );

    public function userPosts(User $user);
}
