<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ExchangeRateRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\ExchangeRateService;
use App\Entity\ExchangeRate;


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

        // Получение курсов валюты USD/RUB
        $ratesUsd = $repository->findBy(
            ['type' => ExchangeRate::TYPE_USD],
            ['date' => 'ASC']
        );

        // Получение курсов валюты UER/RUB
        $ratesEur = $repository->findBy(
            ['type' => ExchangeRate::TYPE_EUR],
            ['date' => 'ASC']
        );

        // Преобразование данных для графика UER/RUB
        $chartDataUsd = [
            'series'      => implode(',', array_map(fn($rate) => (float)$rate->getValue(), $ratesUsd)),
            'categories'  => implode(',', array_map(
                fn($rate) => '"'.(new \DateTime())->setTimestamp($rate->getDate())->format('Y-m-d').'"',
                $ratesUsd
            ))
        ];

        // Преобразование данных для графика UER/RUB
        $chartDataEur = [
            'series'      => implode(',', array_map(fn($rate) => (float)$rate->getValue(), $ratesEur)),
            'categories'  => implode(',', array_map(
                fn($rate) => '"'.(new \DateTime())->setTimestamp($rate->getDate())->format('Y-m-d').'"',
                $ratesEur
            ))
        ];
        
        return $this->render('pages/home.html.twig', [
            'disk' => [
                'total'        => $diskTotal,
                'used_percent' => $diskUsagePercent
            ],
            'ram' => [
                'total'        => $totalMem,
                'used_percent' => $memUsagePercent
            ],
            'chart' => [
                'usd'       => $chartDataUsd,
                'eur'       => $chartDataEur
            ],
            'rates' => $rates
        ]);
    }
}
