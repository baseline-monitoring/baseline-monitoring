<?php

namespace App\Command;

use App\Repository\Read\BaselineConfigurationRepository;
use App\Repository\Read\BaselineStatisticResultRepository;
use App\Service\GitService;
use App\Service\Parser\PHPStanParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:test')]
class TestCommand extends Command
{
    public function __construct(
        private readonly BaselineConfigurationRepository $baselineConfigurationRepository,
        private readonly GitService $gitService,
        private readonly PHPStanParser $phpStanParser,
        private BaselineStatisticResultRepository $baselineStatisticResultRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Test Command');

        $baselineConfiguration = $this->baselineConfigurationRepository->find(1);

        if (null === $baselineConfiguration) {
            $io->error('Baseline Configuration not found');

            return Command::FAILURE;
        }

//        dump($this->phpStanParser->getVersion('/var/www/baseline-monitoring/var/project_checkout_directory/1-main_baseline/scripts/phpstan-api.neon'));
//        $commits = $this->gitService->findBaselineCommits($baselineConfiguration);

        $result = $this->baselineStatisticResultRepository->getChartDataForBaselineConfiguration($baselineConfiguration);

        dump($result);

        $io->success('Ok');

        return Command::SUCCESS;
    }
}
