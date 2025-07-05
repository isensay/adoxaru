<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct(ParameterBagInterface $params, string $defaultLocale = 'ru')
    {
        $this->defaultLocale    = $params->get('kernel.default_locale');
        $this->supportedLocales = $params->get('app.supported_locales');
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $response = new Response();
        $request = $event->getRequest();

        $localeCookie = $request->cookies->get('user_locale');
        
        $locale = in_array($localeCookie, $this->supportedLocales)
        ? $localeCookie 
        : $this->defaultLocale;
        
        $this->defaultLocale = $locale;
        
        if ($locale = $request->attributes->get('_locale')) {
            $this->updateLocale($request, $locale);
            return;
        }
        
        // Пробуем получить локаль из сессии
        if ($locale = $request->getSession()->get('_locale')) {
            $request->setLocale($locale);
            
        } else {
            // Или используем дефолтную
            $request->setLocale($this->defaultLocale);
            $locale = $this->defaultLocale;
        }

        if ($localeCookie <> $locale)
        {
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
        }
    }

    /*
    public function onLogout(LogoutEvent $event): void
    {
        #dd("stop");
        $request = $event->getRequest();
        $response = $event->getResponse() ?? new Response();
        $session = $request->getSession();

        $locale = $request->getLocale();
        
        $event->setResponse(
            new \Symfony\Component\HttpFoundation\RedirectResponse(
                "/login?locale=$locale"
            )
        );

    }
        */

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
            #LogoutEvent::class => ['onLogout', 64],
        ];
    }
}
