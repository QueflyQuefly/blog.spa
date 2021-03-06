<?php

namespace App\Api\Controller;

use App\Normalizer\PostNormalizer;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', 'api_post_')]
class PostController extends AbstractController 
{
    private PostService $postService;

    private PostNormalizer $postNormalizer;

    public function __construct(
        PostService $postService,
        PostNormalizer $postNormalizer
    ) {
        $this->postService = $postService;
        $this->postNormalizer = $postNormalizer;
    }

    #[Route('/last/{numberOfPosts<(?!0)\b[0-9]+>?10}', 'last')]
    public function getLastPostsInJson(int $numberOfPosts): Response
    {
        $lastPosts = $this->postService->getLastPosts($numberOfPosts);
        $lastPosts = $this->postNormalizer->normalizeArrayOfPosts($lastPosts);

        return new JsonResponse($lastPosts);
    }

    #[Route('/more_talked/{numberOfPosts<(?!0)\b[0-9]+>?3}', 'more_talked')]
    public function getMoreTalkedPostsInJson(int $numberOfPosts): Response
    {
        $moreTalkedPosts = $this->postService->getMoreTalkedPosts($numberOfPosts);
        $moreTalkedPosts = $this->postNormalizer->normalizeArrayOfPosts($moreTalkedPosts);

        return new JsonResponse($moreTalkedPosts);
    }

    #[Route('/{postId<(?!0)\b[0-9]+>}', 'one')]
    public function getPostByIdInJson(int $postId): Response
    {
        $post = $this->postService->getPostById($postId);
        
        if ($post !== null) {
            $post = $this->postNormalizer->normalize($post);
        }

        return new JsonResponse($post);
    }
}