<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private string $maintenanceFlag,
        private array $allowedIps = [],
        private ?Profiler $profiler = null
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->maintenanceFlag !== 'on') {
            return;
        }

        $ip = $event->getRequest()->getClientIp();
        if (in_array($ip, $this->allowedIps)) return;

        if ($this->profiler !== null) {
            $this->profiler->disable();
        }

        $request = $event->getRequest();
        $locale  = $request->getLocale();

        $content = $this->twig->render('pages/maintenance.html.twig', [
            'locale' => $locale
        ]);

        $event->setResponse(new Response($content, 503));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
        ];
    }
}