<?php

namespace App\Service;

use App\Entity\ExchangeRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExchangeRateService
{
    private const API_URL = 'https://www.cbr-xml-daily.ru/daily_json.js';
    private const MAX_RETRIES = 12; // 2 часа с интервалом 10 минут
    private const RETRY_INTERVAL = 600; // 10 минут
    private $translator;

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em,
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    public function getRates(): array
    {
        $rates      = [];
        $types      = [1 => 'usd', 2 => 'eur'];
        $today      = date('d.m.Y');
        $yesterday  = date('d.m.Y', time() - 86400);
        $valuePrev = [];

        $repository = $this->em->getRepository(ExchangeRate::class);

        foreach ($repository->getLatestRates() as $rate) {
            $nameType = $types[$rate->getType()];
            if (!isset($rates[$nameType])) {
                $date = date('d.m.Y', $rate->getDate());
                $rates[$nameType] = [
                    'value'      => round($rate->getValue(), 2),
                    'difference' => null,
                    'date'       => $date == $today ? $this->translator->trans('exchange_rates.date', [], 'home') : $date
                ];
            } else {
                $valuePrev[$nameType] = round($rate->getValue(), 2);
            }
        }

        foreach($rates as $nameType => $data) {
            if (isset($valuePrev[$nameType])) {
                $rates[$nameType]['difference'] = round($rates[$nameType]['value'] - $valuePrev[$nameType], 2);
            }
        }

        foreach($types as $nameType)
        {
            if (!isset($rates[$nameType]))
            {
                $rates[$nameType] = [
                    'value'      => null,
                    'difference' => null,
                    'date'       => null
                ];
            }
        }
        
        return $rates;
    }

    public function updateRates(): bool
    {
        $attempt = 0;
        $success = false;

        while ($attempt < self::MAX_RETRIES && !$success) {
            try {
                $response = $this->client->request('GET', self::API_URL);
                $data = $response->toArray();

                $currentDate  = new \DateTime();
                $rateUnixtime = (int)strtotime($data['Date']);
                $rateUnixtime = (int)strtotime(date('Y-m-d', $rateUnixtime).'T12:05:00');

                // Проверяем, что дата курса не в будущем
                if (date('Y-m-d', $rateUnixtime) > date('Y-m-d')) {
                    print('Rate date is in future: '.date('Y-m-d', $rateUnixtime));
                    return false;
                }

                $rateDate = new \DateTime(date('Y-m-d', $rateUnixtime).'T12:05:00.0 +03:00');

                // Проверяем, есть ли уже курсы USD на эту дату
                $existingUsd = $this->em->getRepository(ExchangeRate::class)->findOneBy([
                    'date' => $rateDate->getTimestamp(),
                    'type' => ExchangeRate::TYPE_USD
                ]);
               
                if ($existingUsd) {
                    print('Rates USD for already exist');
                }
                else
                {
                    $connection = $this->em->getConnection();
                    $connection->transactional(function() use ($data, $rateDate) {
                        $this->saveRate(ExchangeRate::TYPE_USD, $data['Valute']['USD']['Value'], $rateDate->getTimestamp());
                    });
                }

                // Проверяем, есть ли уже курсы USD на эту дату
                $existingEur = $this->em->getRepository(ExchangeRate::class)->findOneBy([
                    'date' => $rateDate->getTimestamp(),
                    'type' => ExchangeRate::TYPE_EUR
                ]);

                if ($existingEur) {
                    print('Rates EUR for already exist');
                }
                else
                {
                    $connection = $this->em->getConnection();
                    $connection->transactional(function() use ($data, $rateDate) {
                        $this->saveRate(ExchangeRate::TYPE_EUR, $data['Valute']['EUR']['Value'], $rateDate->getTimestamp());
                    });
                }

                $success = true;
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt < self::MAX_RETRIES) {
                    sleep(self::RETRY_INTERVAL);
                }
            }
        }

        return $success;
    }

    private function saveRate(int $type, float $value, int $date): void
    {
        $rate = new ExchangeRate();
        $rate->setType($type);
        $rate->setValue((string)$value);
        $rate->setDate($date);

        $this->em->persist($rate);
        $this->em->flush();
    }
}