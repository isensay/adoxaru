<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ExchangeRateRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\ExchangeRateService;


final class HomeController extends AbstractController
{
    private $translator;
    private ExchangeRateService $exchangeRateService;

    public function __construct(
        TranslatorInterface $translator,
        ExchangeRateService $exchangeRateService
    ) {
        $this->translator = $translator;
        $this->exchangeRateService = $exchangeRateService;
    }

    #[Route('/', name: 'app_home')]
    public function index(ExchangeRateRepository $repository): Response
    {
        // Получение информации о курсах валют
        $rates = $this->exchangeRateService->getRates();

        // Получение информации о диске
        $diskTotal = round(disk_total_space('/') / (1024 * 1024 * 1024), 2); // в ГБ
        $diskFree  = round(disk_free_space('/')  / (1024 * 1024 * 1024), 2); // в ГБ
        $diskUsed  = $diskTotal - $diskFree;
        $diskUsagePercent = round(($diskUsed / $diskTotal) * 100);

        // Получение информации о памяти
        $memoryInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $memoryInfo, $matches);
        $totalMem = round($matches[1] / (1024 * 1024)); // в ГБ
        preg_match('/MemAvailable:\s+(\d+)/', $memoryInfo, $matches);
        $freeMem = round($matches[1] / (1024 * 1024)); // в ГБ
        $usedMem = $totalMem - $freeMem;
        $memUsagePercent = round(($usedMem / $totalMem) * 100);
        
        return $this->render('pages/home.html.twig', [
            'disk' => [
                'total'        => $diskTotal,
                'used_percent' => $diskUsagePercent
            ],
            'ram' => [
                'total'        => $totalMem,
                'used_percent' => $memUsagePercent
            ],
            'rates' => $rates
        ]);
    }
}
