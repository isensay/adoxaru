<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Cookie;

class SecurityController extends AbstractController
{
    #[Route(
        path: '/login',
        name: 'app_login',
        requirements: ['locale' => 'en|ru'],
        defaults: ['locale' => 'ru']
    )]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $localeCookie = $request->cookies->get('user_locale');

        $locale = in_array($localeCookie, $this->getParameter('app.supported_locales'))
        ? $localeCookie 
        : $this->getParameter('kernel.default_locale');

        $request->setLocale($locale);

        $response = new Response();
        $response->headers->setCookie(
            new Cookie(
                'user_locale', // Имя параметра
                $locale,       // Значение
                time() + 86400 * 365, // Срок действия (1 год)
                '/',           // Путь
                null,          // Домен (null для текущего)
                false,         // HTTPS-only
                false,         // httpOnly
                false,         // raw
                Cookie::SAMESITE_LAX
            )
        );
        $response->sendHeaders();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Или с параметрами:
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
