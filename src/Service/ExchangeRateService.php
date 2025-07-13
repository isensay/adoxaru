<?php

namespace App\Service;

use App\Entity\ExchangeRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private const API_URL = 'https://www.cbr-xml-daily.ru/daily_json.js';
    private const MAX_RETRIES = 12; // 2 часа с интервалом 10 минут
    private const RETRY_INTERVAL = 600; // 10 минут

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em
    ) {}

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