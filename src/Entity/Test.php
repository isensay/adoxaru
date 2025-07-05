<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(
    name: 'test',
    indexes: [
        new ORM\Index(name: 'test_name',        columns: ['test_name']),
        new ORM\Index(name: 'test_description', columns: ['test_description'], flags: ['fulltext']),
        new ORM\Index(name: 'test_date',        columns: ['test_date']),
        new ORM\Index(name: 'test_status',      columns: ['test_status'])
    ],
    options: [
        'comment'   => 'Таблица с тестами',
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'engine'    => 'InnoDB'
    ]
)]
#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'test_id', options: ['comment' => 'ID теста'])]
    private ?int $id = null;

    #[ORM\Column(
        name: 'test_name',
        type: 'string',
        length: 255,
        options: ['comment' => 'Название теста']
    )]
    private string $name = '';

    #[ORM\Column(
        name: 'test_description',
        type: 'text',
        options: ['comment' => 'Описание теста']
    )]
    private string $description;

    #[ORM\Column(
        name: 'test_date',
        type: 'integer',
        options: [
            'unsigned' => true,
            'comment'  => 'Дата и время создания теста'
            ]
    )]
    private int $date;

    #[ORM\Column(
        name: 'test_status',
        type: 'boolean',
        options: [
            'default' => false,
            'comment' => 'Статус теста'
        ]
    )]
    private bool $status = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }





}
