<?php

namespace App\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', 'api_post_')]
class PostController extends AbstractController {
    private $postService;

    public function __construct()
    {

    }

    #[Route('/last/{numberOfPosts<(?!0)\b[0-9]+>?10}', 'last')]
    public function getLastPostsInJson(int $numberOfPosts): Response
    {
        $lastPosts = '$this->postService->getLastPosts($numberOfPosts)';

        return new JsonResponse($numberOfPosts);
    }
}