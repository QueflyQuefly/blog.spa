<?php

namespace App\Service;

use App\Repository\PostRepository;

class PostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getLastPosts(int $numberOfPosts)
    {
        return $this->postRepository->findLastPosts($numberOfPosts);
    }
}