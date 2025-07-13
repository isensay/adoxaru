<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
#[ORM\Table(name: 'exchange_rates')]
#[ORM\Index(columns: ['exchange_rates_type', 'exchange_rates_date'], name: 'exchange_rate_idx')]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'exchange_rates_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'exchange_rates_type', type: 'smallint')]
    private int $type; // 1 - USD, 2 - EUR

    #[ORM\Column(name: 'exchange_rates_value', type: 'decimal', precision: 10, scale: 4)]
    private string $value;

    #[ORM\Column(name: 'exchange_rates_date', type: 'integer')]
    private int $date;

    public const TYPE_USD = 1;
    public const TYPE_EUR = 2;

    // Добавляем геттеры и сеттеры
    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
