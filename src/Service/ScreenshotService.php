<?php

// src/Service/ScreenshotService.php
namespace App\Service;

use App\Entity\Screenshot;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Intervention\Image\ImageManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormFactoryInterface;

class ScreenshotService
{
    private string $screenshotsDir;
    private string $projectDir;
    private Client $httpClient;
    private FormFactoryInterface $formFactory;
    private Filesystem $filesystem; // Добавляем свойство

    public function __construct(
        string $projectDir,
        Client $httpClient,
        Filesystem $filesystem // Добавляем в конструктор
    ) {
        $this->projectDir = $projectDir;
        $this->httpClient = $httpClient;
        $this->filesystem = $filesystem; // Инициализируем
        $this->screenshotsDir = $this->projectDir.'/public/uploads/screenshots/';
    }

    public function capture(string $url): array
    {
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        try {
            // Создаем директорию через Filesystem
            $this->filesystem->mkdir($this->screenshotsDir);

            $response = $this->httpClient->post('screenshot', [
                'json' => [
                    'url' => $url,
                    'options' => [
                        'type' => 'jpeg',
                        'quality' => 80
                    ]
                ],
                'timeout' => 30
            ]);

            $filename = md5(uniqid()).'.jpg';
            $filepath = $this->screenshotsDir.$filename;

            // Сохраняем файл через Filesystem
            $this->filesystem->dumpFile($filepath, $response->getBody());

            return [
                'filename' => $filename,
                'filepath' => $filepath,
                'filesize' => filesize($filepath)
            ];

        } catch (\Exception $e) {
            throw new \RuntimeException('Browserless error: ' . $e->getMessage());
        }
    }

    public function deleteScreenshotFile(string $filename): void
    {
        $filePath = $this->screenshotsDir . $filename;
        
        if ($this->filesystem->exists($filePath)) {
            $this->filesystem->remove($filePath);
        }
    }
}