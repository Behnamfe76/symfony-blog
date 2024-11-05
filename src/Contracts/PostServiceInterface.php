<?php

namespace App\Contracts;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostFormType;

interface PostServiceInterface
{
    public function storeTextualPost(Post $post, CreatePostFormType $form, User $user);
}