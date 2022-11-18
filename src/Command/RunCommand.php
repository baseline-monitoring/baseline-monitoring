<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\BaselineStatisticResult;
use App\Repository\Read\BaselineConfigurationRepository;
use App\Repository\Read\BaselineStatisticResultRepository;
use App\Repository\Write\BaselineStatisticResultManager;
use App\Service\BaselineParser;
use App\Service\GitService;
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
        private readonly BaselineParser $baselineParser,
        private readonly BaselineConfigurationRepository $baselineConfigurationRepository,
        private readonly GitService $gitService,
        private readonly BaselineStatisticResultRepository $baselineStatisticResultRepository,
        private readonly BaselineStatisticResultManager $baselineStatisticResultManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $baselineConfiguration = $this->baselineConfigurationRepository->find(1);

        if (null === $baselineConfiguration) {
            $io->error('BaseConfiguration not found');

            return Command::FAILURE;
        }

        $io->title('Analyze Baseline');

        $this->gitService->pull($baselineConfiguration);
        $commitHashes = $this->gitService->findBaselineCommits($baselineConfiguration);

        $countHashes = count($commitHashes->getCommitHashes());
        $io->comment('Found ' . $countHashes . ' baseline changes in the last year');

        $counter = 1;
        $rows = [];
        foreach ($commitHashes->getCommitHashes() as $commitHash) {
            $io->comment('Process hash ' . $counter . ' / ' . $countHashes . ' => ' . $commitHash->getHash());

            if ($this->baselineStatisticResultRepository->findOneBy(['commitHash' => $commitHash->getHash()]) !== null) {
                $io->comment('Hash ' . $commitHash->getHash() . ' already processed. Skip it');

                ++$counter;
                continue;
            }

            $this->gitService->checkoutCommit($baselineConfiguration, $commitHash->getHash());

            $directory = $this->gitService->getProjectCheckoutDirectoryFilepath($baselineConfiguration);

            $baselineFile = $directory . '/' . $baselineConfiguration->getPathToBaseline();

            $io->comment('Analyze "' . $baselineFile . '"');
            $statisticResultCollection = $this->baselineParser->getStatisticsForFiles([$baselineFile]);

            foreach ($statisticResultCollection->getStatisticResults() as $statisticResult) {
                $io->comment('Save statistic result for ' . $commitHash->getHash());
                $this->baselineStatisticResultManager->save(
                    new BaselineStatisticResult(
                        $baselineConfiguration,
                        $statisticResult->getCommutativeErrors(),
                        $statisticResult->getUniqueErrors(),
                        $commitHash->getHash(),
                        $commitHash->getCommitDate(),
                    )
                );

                $rows[] = [
                    $commitHash->getHash(),
                    $commitHash->getCommitDate()->format('Y-m-d H:i:s'),
                    basename($statisticResult->getFileName()),
                    $statisticResult->getCommutativeErrors(),
                    $statisticResult->getUniqueErrors(),
                ];
            }

            ++$counter;
        }

        $io->table(
            ['commit hash', 'commit date', 'file name', 'cummultative errors', 'unique errors'],
            $rows
        );

        $io->success('Ok');

        return Command::SUCCESS;
    }
}
