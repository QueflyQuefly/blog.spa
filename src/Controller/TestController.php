<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', 'test')]
    public function test(): Response
    {
        function someGenerator(int $n) {
            for ($i = 0; $i < $n; $i++) {
                yield $i;
            }
        }

        $string = '';

        foreach (someGenerator(50) as $value) {
            $string .= $value . ' ';
        }

        $string .= '  ' . memory_get_usage() / 1024 / 1024;

        return $this->render('test/test.html.twig', ['string' => $string]);
    }
}