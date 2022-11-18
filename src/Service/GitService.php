<?php

namespace App\Service;

use App\Dto\Git\CommitHash;
use App\Dto\Git\CommitHashCollection;
use App\Entity\BaselineConfiguration;
use App\Exception\ProcessFailedException;
use DateTimeImmutable;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

use function Symfony\Component\String\u;

class GitService
{
    private const PROCESS_TIMEOUT_SECONDS = 3600;

    public function __construct(
        private readonly string $projectCheckoutDirectory,
        private readonly Filesystem $filesystem
    ) {
    }

    private function createSSHKeyFile(BaselineConfiguration $baselineConfiguration): void
    {
        $sshDirectory = $this->getProjectCheckoutDirectorySSHFilepath($baselineConfiguration);

        $this->filesystem->remove($sshDirectory);
        $this->filesystem->mkdir($sshDirectory);

        $sshFile = $this->getSSHKeyFilepath($baselineConfiguration);

        $privateKeyContent = $baselineConfiguration->getRemoteServer()->getPrivateKey();

        /*
         * If the key file contains "^M" element its format will be invalid.
         *
         * Therefore correct it by default
         */
        $privateKeyContent = strtr($privateKeyContent, ["\r" => '']);
        $this->filesystem->dumpFile($sshFile, $privateKeyContent . PHP_EOL . PHP_EOL);
        $this->filesystem->chmod($sshFile, 0600);
    }

    private function getSSHKeyFilepath(BaselineConfiguration $baselineConfiguration): string
    {
        return $this->getProjectCheckoutDirectorySSHFilepath($baselineConfiguration) . '/private_ssh_key';
    }

    /**
     * @throws ProcessFailedException
     */
    public function clone(BaselineConfiguration $baselineConfiguration): void
    {
        $this->createSSHKeyFile($baselineConfiguration);

        $process = [
            'git',
            'clone',
            $baselineConfiguration->getRepositoryUrl(),
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration),
        ];
        $process = new Process(
            command: $process,
            env: [
                'GIT_SSH_COMMAND' => 'ssh -i ' . $this->getSSHKeyFilepath($baselineConfiguration) . ' -o IdentitiesOnly=yes -o StrictHostKeyChecking=no',
            ]
        );
        $process->setTimeout(self::PROCESS_TIMEOUT_SECONDS);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function pull(BaselineConfiguration $baselineConfiguration): void
    {
        $this->createSSHKeyFile($baselineConfiguration);

        $process = new Process(
            ['git', 'checkout', '-f', $baselineConfiguration->getMainBranch()],
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration),
            [
                'GIT_SSH_COMMAND' => 'ssh -i ' . $this->getSSHKeyFilepath($baselineConfiguration) . ' -o IdentitiesOnly=yes -o StrictHostKeyChecking=no',
            ]
        );
        $process->setTimeout(self::PROCESS_TIMEOUT_SECONDS);
        $process->run();

        $process = new Process(
            ['git', 'pull'],
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration),
            [
                'GIT_SSH_COMMAND' => 'ssh -i ' . $this->getSSHKeyFilepath($baselineConfiguration) . ' -o IdentitiesOnly=yes -o StrictHostKeyChecking=no',
            ]
        );
        $process->setTimeout(self::PROCESS_TIMEOUT_SECONDS);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function findBaselineCommits(BaselineConfiguration $baselineConfiguration): CommitHashCollection
    {
        $process = new Process(
            [
                'git',
                '--no-pager',
                'log',
                '--first-parent',
                '--remotes',
                '--reflog',
                '--reverse',
                '--since="1 year ago"',
                '--pretty=format:"%H %ad"',
                '--date=format:"%Y-%m-%d %H:%M:%S"',
                '--',
                $baselineConfiguration->getPathToBaseline(),
            ],
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration),
        );
        $process->setTimeout(self::PROCESS_TIMEOUT_SECONDS);
        $process->run();

        $output = $process->getOutput();

        if ($output === '') {
            return new CommitHashCollection();
        }

        $commitHashCollection = new CommitHashCollection();

        foreach (explode(PHP_EOL, $output) as $hashLine) {
            $hashLine = str_replace('"', '', $hashLine);

            $hashLineParts = explode(' ', $hashLine);

            $commitHashCollection->addCommitHash(new CommitHash(
                $hashLineParts[0] ?? '',
                new DateTimeImmutable($hashLineParts[1] . ' ' . $hashLineParts[2])
            ));
        }

        return $commitHashCollection;
    }

    public function checkoutCommit(BaselineConfiguration $baselineConfiguration, string $commitHash): void
    {
        $process = new Process(
            ['git', 'config', 'core.sparsecheckout', 'true'],
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration)
        );
        $process->run();

        $this->filesystem->dumpFile(
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration) . '/.git/info/sparse-checkout',
            implode(PHP_EOL, [
                $baselineConfiguration->getPathToBaseline(),
                $baselineConfiguration->getPathToConfiguration(),
            ])
        );

        $process = new Process(
            ['git', 'read-tree', '--reset', '-u', $commitHash],
            $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration)
        );
        $process->setTimeout(self::PROCESS_TIMEOUT_SECONDS);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    private function getProjectCheckoutDirectorySSHFilepath(BaselineConfiguration $baselineConfiguration): string
    {
        return $this->getProjectCheckoutDirectoryFilepath($baselineConfiguration) . '-ssh';
    }

    public function getProjectCheckoutDirectoryFilepath(BaselineConfiguration $baselineConfiguration): string
    {
        $directory = $this->prepareCheckoutDirectoryPath();

        // use the id as prefix, in case another configuration has the same name
        $directoryName = $baselineConfiguration->getId() . '-' . u($baselineConfiguration->getName())
            ->collapseWhitespace()
            ->snake();

        return $directory . $directoryName;
    }

    private function prepareCheckoutDirectoryPath(): string
    {
        // needed in case someone overrides the value via env
        return rtrim($this->projectCheckoutDirectory, '/') . '/';
    }
}
