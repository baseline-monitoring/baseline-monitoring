<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\BaselineParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:top-errors',
    description: 'Get Top errors of given baseline on filesystem'
)]
class GetTopErrorsCommand extends Command
{
    public function __construct(private readonly BaselineParser $baselineParser)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('baseline-file', 'f', InputOption::VALUE_REQUIRED, 'Path to baseline file')
            ->addOption('amount-top-errors', 'a', InputOption::VALUE_OPTIONAL, 'Amount of top errors (default: 10)', 10)
            ->addOption('aggregate-errors', 'g', InputOption::VALUE_NONE, 'Aggregate errors, files and counts');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string $baselineFile */
        $baselineFile = $input->getOption('baseline-file');
        if (!file_exists($baselineFile)) {
            $io->error('Baseline file "' . $baselineFile . '" does not exist!');

            return Command::FAILURE;
        }

        /** @var int $amountTopErrors */
        $amountTopErrors = $input->getOption('amount-top-errors');
        if ($amountTopErrors <= 0) {
            $io->error('Amount of errors has to be bigger than 0! Default value is 10');
        }

        $title = 'Top ' . $amountTopErrors . ' Errors';
        $rows = $this->getTopErrorRows($baselineFile, $amountTopErrors);

        if ($input->getOption('aggregate-errors')) {
            $title = 'Aggregate Top ' . $amountTopErrors . ' Errors';
            $rows = $this->getAggregatedErrorRows($baselineFile, $amountTopErrors);
        }

        $io->title($title);
        $io->table(['Message', 'Path', 'Count'], $rows);

        $io->success('OK');

        return Command::SUCCESS;
    }

    /**
     * @return array<array{0: string, 1: string, 2: int}>
     */
    private function getAggregatedErrorRows(string $baselineFile, int $amountTopErrors): array
    {
        $baselineEntries = $this->baselineParser->getParsedErrors($baselineFile);

        $rows = [];
        foreach ($baselineEntries->getBaselineEntries() as $baselineEntry) {
            $message = $baselineEntry->getMessage();
            // $path = $baselineEntry->getPath();
            $count = $baselineEntry->getCount();

            $rows[$message] = [
                $message,
                '', // isset($rows[$message][1]) ? $rows[$message][1] . ', ' . $path : $path,
                ($rows[$message][2] ?? 0) + $count,
            ];
        }

        usort($rows, static fn (array $first, array $second): int => $second[2] <=> $first[2]);

        return array_slice($rows, 0, $amountTopErrors);
    }

    /**
     * @return array<array{0: string, 1: string, 2: int}>
     */
    private function getTopErrorRows(string $baselineFile, int $amountTopErrors): array
    {
        $baselineEntries = $this->baselineParser->getParsedErrors($baselineFile)
            ->sortByCount()
            ->getFirstEntries($amountTopErrors);

        $rows = [];
        foreach ($baselineEntries->getBaselineEntries() as $baselineEntry) {
            $rows[] = [
                $baselineEntry->getMessage(),
                $baselineEntry->getPath(),
                $baselineEntry->getCount(),
            ];
        }

        return $rows;
    }
}
