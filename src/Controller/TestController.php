<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(LoggerInterface $logger): Response
    {
        $logger->error('PRODUCTION TEST LOG');
        return $this->render('test/test.html.twig', [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    #[Route('/test_api', name: 'app_test_api')]
    public function api(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}
