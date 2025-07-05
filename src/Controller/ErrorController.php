<?php

namespace App\Controller;

#use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#use Symfony\Component\HttpFoundation\Response;
#use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
#use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Symfony\Component\HttpKernel\Exception\{
    HttpExceptionInterface,
    HttpException,
    UnauthorizedHttpException,
    AccessDeniedHttpException,
    NotFoundHttpException
};

use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    public function show(\Throwable $exception): Response
    {
        $statusCode = $exception instanceof HttpExceptionInterface 
            ? $exception->getStatusCode() 
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        #return $this->render('bundles/TwigBundle/Exception/error' . $statusCode . '.html.twig', [
        return $this->render('pages/error.html.twig', [
            'status_code' => $statusCode,
            'error_image' => (in_array($statusCode, [401,403,404,419,500])) ? "/source/base/images/errors/{$statusCode}.svg" : '/source/base/images/errors/500.svg',
            'exception'   => $exception
        ], new Response('', $statusCode));
    }

    #[Route('/maintenance', name: 'test_maintenance')]
    public function maintenance(): Response
    {
        return $this->render('pages/maintenance.html.twig', [], new Response('', 503));
    }

    #[Route('/comming_soon', name: 'test_comming_soon')]
    public function comming_soon(): Response
    {
        return $this->render('pages/comming_soon.html.twig', [], new Response('', 200));
    }

    #[Route('/error401', name: 'test_error_401')]
    public function trigger401(): void
    {
        throw new UnauthorizedHttpException('Bearer', 'Требуется авторизация');
    }

    #[Route('/error403', name: 'test_error_403')]
    public function trigger403(): void
    {
        throw new AccessDeniedHttpException('Доступ запрещен');
    }

    #[Route('/error404', name: 'test_error_404')]
    public function trigger404(): void
    {
        throw new NotFoundHttpException('Страница не найдена');
    }

    #[Route('/error419', name: 'test_error_419')]
    public function trigger419(): void
    {
        throw new HttpException(419, 'Срок действия страницы истек');
    }

    #[Route('/error500', name: 'test_error_500')]
    public function trigger500(): void
    {
        throw new \RuntimeException('Внутренняя ошибка сервера');
    }
}
