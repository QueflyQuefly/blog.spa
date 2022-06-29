<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', 'homepage')]
    public function homepage(): Response
    {
        return $this->render('homepage.html.twig');
    }

    #[Route('/post/{postId<(?!0)\b[0-9]+>}', 'post')]
    public function showPost(int $postId): Response
    {
        return $this->render('post/post.html.twig');
    }
}