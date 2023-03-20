<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\BaselineErrors;
use App\Entity\BaselineStatisticResult;
use App\Repository\Read\BaselineConfigurationRepository;
use App\Repository\Read\BaselineStatisticResultRepository;
use App\Repository\Write\BaselineErrorsManager;
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
        private readonly BaselineStatisticResultManager $baselineStatisticResultManager,
        private readonly BaselineErrorsManager $baselineErrorsManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $baselineConfigurations = $this->baselineConfigurationRepository->findAll();

        foreach ($baselineConfigurations as $baselineConfiguration) {
            $io->title('Analyze Baseline "' . ($baselineConfiguration->getName() ?? '') . '"');

            $this->gitService->cloneIfNotExists($baselineConfiguration);
            $this->gitService->pull($baselineConfiguration);
            $commitHashes = $this->gitService->findBaselineCommits($baselineConfiguration);

            $countHashes = count($commitHashes->getCommitHashes());
            $io->comment('Found ' . $countHashes . ' baseline changes in the last year');

            $counter = 1;
            $rows = [];
            $directory = $this->gitService->getProjectCheckoutDirectoryFilepath($baselineConfiguration);
            $baselineFile = $directory . '/' . $baselineConfiguration->getPathToBaseline();
            $configurationFile = $directory . '/' . $baselineConfiguration->getPathToConfiguration();
            $io->comment('Analyze "' . $baselineFile . '"');

            foreach ($commitHashes->getCommitHashes() as $commitHash) {
                $io->comment('Process hash ' . $counter . ' / ' . $countHashes . ' => ' . $commitHash->getHash());

                if ($this->baselineStatisticResultRepository->findOneBy(['commitHash' => $commitHash->getHash()]) !== null) {
                    $io->comment('Hash ' . $commitHash->getHash() . ' already processed. Skip it');

                    ++$counter;
                    continue;
                }

                $this->gitService->checkoutCommit($baselineConfiguration, $commitHash->getHash());

                $statisticResultCollection = $this->baselineParser->getStatisticForFile($baselineFile, $configurationFile);

                foreach ($statisticResultCollection->getStatisticResults() as $statisticResult) {
                    $io->comment('Save statistic result for ' . $commitHash->getHash());
                    $this->baselineStatisticResultManager->save(
                        new BaselineStatisticResult(
                            $baselineConfiguration,
                            $statisticResult->getCommutativeErrors(),
                            $statisticResult->getUniqueErrors(),
                            $commitHash->getHash(),
                            $commitHash->getCommitDate(),
                            $statisticResult->getVersion()
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

            // save errors for last hash
            $baselineEntryCollection = $this->baselineParser->parseFile($baselineFile, $configurationFile);

            if ($baselineEntryCollection->getCount() === 0) {
                $io->error('Baseline empty');
                continue;
            }

            $this->baselineErrorsManager->deleteErrorsForBaselineConfiguration($baselineConfiguration);
            $errorCounter = 0;
            foreach ($baselineEntryCollection->getBaselineEntries() as $baselineEntry) {
                $this->baselineErrorsManager->addError(
                    new BaselineErrors(
                        $baselineConfiguration,
                        $baselineEntry->getMessage(),
                        $baselineEntry->getCount(),
                        $baselineEntry->getPath()
                    )
                );

                if (($errorCounter % BaselineErrorsManager::BATCH_SIZE) === 0) {
                    $errorCounter = 0;
                    $this->baselineErrorsManager->flush();
                }

                ++$errorCounter;
            }
            $this->baselineErrorsManager->flush();

            $io->table(
                ['commit hash', 'commit date', 'file name', 'cummultative errors', 'unique errors'],
                $rows
            );
        }

        $io->success('Ok');

        return Command::SUCCESS;
    }
}
