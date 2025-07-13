<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
use App\Service\ExchangeRateService;

// src/Command/UpdateExchangeRatesCommand.php
#[AsCommand(
    name: 'app:update-exchange-rates',
    description: 'Updates currency exchange rates from CBR API'
)]
class UpdateExchangeRatesCommand extends Command
{
    public function __construct(
        private ExchangeRateService $exchangeRateService,
        private LoggerInterface $logger // Теперь используется правильный интерфейс
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        if ($this->exchangeRateService->updateRates()) {
            $io->success('Exchange rates updated successfully');
            return Command::SUCCESS;
        }

        $this->logger->error('Failed to update exchange rates after multiple attempts');
        $io->error('Failed to update exchange rates');
        return Command::FAILURE;
    }
}
