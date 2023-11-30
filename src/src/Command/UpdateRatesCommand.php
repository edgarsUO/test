<?php declare(strict_types=1);

namespace App\Command;

use App\Service\RatesUpdateService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'app:update-rates',
    description: 'Update currency rates',
)]
class UpdateRatesCommand extends Command
{
    public function __construct(private readonly RatesUpdateService $ratesService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->ratesService->updateRates();
        } catch (Throwable $exception) {
            $output->writeln(sprintf('Rates update failed: %s', $exception->getMessage()));

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
