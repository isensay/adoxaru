<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ExchangeRateRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

#use App\Entity\ExchangeRate;
#use Doctrine\ORM\EntityManagerInterface;

final class HomeController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator) //, private EntityManagerInterface $em
    {
        $this->translator = $translator;
    }

    #[Route('/', name: 'app_home')]
    public function index(ExchangeRateRepository $repository): Response
    {
        $rates      = [];
        $types      = [1 => 'usd', 2 => 'eur'];
        $today      = date('d.m.Y');
        $yesterday  = date('d.m.Y', time() - 86400);
        $valuePrev = [];

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
        
        return $this->render('pages/home.html.twig', [
            'rates' => $rates
        ]);
    }

    /*
    #[Route('/insert_rates', name: 'app_insert_rates')]
    public function insert_rates(): Response
    {
        print '<html style="background:#282923; color:#ccc;">';

        $rates = [];

        foreach([1 => 'usd', 2 => 'eur'] as $typeId => $typeName)
        {
            $row = 1;

            if (($handle = fopen("{$typeName}.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    $num = count($data);

                    $date  = preg_replace('/\x{FEFF}/u', '', $data[0]);
                    $value = (float)$data[2];

                    if (mb_strlen($date) == 10 || $value > 0)
                    {
                        $dt = explode('.', $date);
                        $dateKey  = $dt[2].'-'.$dt[1].'-'.$dt[0];
                        $unixtime = strtotime($dateKey.'T12:05:00');
                        $rates[$dateKey][$typeId] = $value;
                    }
                    $row++;
                }

                fclose($handle);
            }
            
        }

        ksort($rates);

        foreach($rates as $date => $dateArr)
        {
            $unixtime = strtotime($date.'T12:05:00');
            foreach($dateArr as $type => $value)
            {
                $rate = new ExchangeRate();
                $rate->setType((int)$type);
                $rate->setValue((string)$value);
                $rate->setDate((int)$unixtime);
                $this->em->persist($rate);
            }
        }

        $this->em->flush();

        echo 'OK';

        exit;
    }
    */
    
}
