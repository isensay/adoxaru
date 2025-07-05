<?php

namespace App\Controller;

use App\Entity\Screenshot;
use App\Form\ScreenshotType;
use App\Service\ScreenshotService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScreenshotController extends AbstractController
{
    #[Route('/screenshots', name: 'app_screenshots')]
    public function index(
        Request $request,
        ScreenshotService $screenshotService,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response {
        $form = $this->createForm(ScreenshotType::class);
        $form->handleRequest($request);

        $error = false;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $url = $form->get('url')->getData();
                $result = $screenshotService->capture($url);

                $screenshot = new Screenshot();
                $screenshot->setUrl($url);
                $screenshot->setFilename($result['filename']);
                $screenshot->setFileSize($result['filesize']);
                $screenshot->setUser($this->getUser());

                $em->persist($screenshot);
                $em->flush();

                $this->addFlash('success', 'Screenshot created successfully!');
                return $this->redirectToRoute('app_screenshots');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                $error = true;
            }
        }

        $query = $em->getRepository(Screenshot::class)
            ->createQueryBuilder('s')
            ->where('s.user = :user')
            ->setParameter('user', $this->getUser())
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery();

        $screenshots = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('screenshot/index.html.twig', [
            'form' => $form->createView(),
            'screenshots' => $screenshots,
            'error' => $error
        ]);
    }

    #[Route('/screenshots/{id}/delete', name: 'app_screenshot_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Screenshot $screenshot,
        ScreenshotService $screenshotService,
        EntityManagerInterface $em
    ): Response {
        // Проверка CSRF-токена
        if ($this->isCsrfTokenValid('delete'.$screenshot->getId(), $request->request->get('_token'))) {
            // Удаляем файл через сервис
            $screenshotService->deleteScreenshotFile($screenshot->getFilename());
            
            // Удаляем запись из БД
            $em->remove($screenshot);
            $em->flush();
            
            $this->addFlash('success', 'Screenshot success remove!');
        }

        return $this->redirectToRoute('app_screenshots');
    }
}