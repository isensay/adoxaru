<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class LocaleController extends AbstractController
{
    #[Route('/locale/{lang}', name: 'change_lang')]
    public function changeLanguage(
        string $lang, 
        Request $request,
        SessionInterface $session
    ): RedirectResponse {

        if (!in_array($lang, $this->getParameter('app.supported_locales'))) {
            throw $this->createNotFoundException('Not support this locale');
        }

        // Сохраняем в сессии
        $session->set('_locale', $lang);
        
        // Возвращаем на предыдущую страницу
        return $this->redirect($request->headers->get('referer', '/'));
    }
}
