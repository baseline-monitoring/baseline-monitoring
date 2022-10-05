<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\BaselineParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:run',
    description: 'Collects all information for configured baseline files'
)]
class RunCommand extends Command
{
    public function __construct(
        private BaselineParser $baselineParser
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cliOutput = new SymfonyStyle($input, $output);

        $baselineFiles = [
            __DIR__.'/../../phpstan-api-baseline.neon',
        ];
        $rows = [];
        $statisticResultCollection = $this->baselineParser->getStatisticsForFiles($baselineFiles);

        foreach ($statisticResultCollection->getStatisticResults() as $statisticResult) {
            $rows[] = [
                basename($statisticResult->getFileName()),
                $statisticResult->getCommutativeErrors(),
                $statisticResult->getUniqueErrors(),
            ];
        }

        $cliOutput->table(
            ['file name', 'cummultative errors', 'unique errors'],
            $rows
        );

        $cliOutput->success('Ok');

        return Command::SUCCESS;
    }
}
