<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExchangeRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRate[]    findAll()
 * @method ExchangeRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRate::class);
    }

    public function getLatestRates(): array
    {
        $sql = "
            (SELECT e.* FROM exchange_rates e 
            WHERE e.exchange_rates_type = 1 
            ORDER BY e.exchange_rates_date DESC 
            LIMIT 2)
            UNION ALL
            (SELECT e.* FROM exchange_rates e 
            WHERE e.exchange_rates_type = 2 
            ORDER BY e.exchange_rates_date DESC 
            LIMIT 2)
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

        $rates = [];

        foreach ($stmt->executeQuery()->fetchAllAssociative() as $row) {
            $rate = new ExchangeRate();
            $rate->setType($row['exchange_rates_type']);
            $rate->setValue($row['exchange_rates_value']);
            $rate->setDate($row['exchange_rates_date']);
            $rates[] = $rate;
        }

        return $rates;
    }

    /*
    public function getLatestRates(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }
    */
}