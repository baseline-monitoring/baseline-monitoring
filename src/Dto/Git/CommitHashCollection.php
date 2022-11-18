<?php

namespace App\Dto\Git;

class CommitHashCollection
{
    /**
     * @var CommitHash[]
     */
    private array $commitHashes = [];

    /**
     * @param CommitHash[] $commitHashes
     */
    public function __construct(
        array $commitHashes = []
    ) {
        $this->setCommitHashes($commitHashes);
    }

    /**
     * @return CommitHash[]
     */
    public function getCommitHashes(): array
    {
        return $this->commitHashes;
    }

    /**
     * @param CommitHash[] $commitHashes
     */
    public function setCommitHashes(array $commitHashes): CommitHashCollection
    {
        $this->commitHashes = [];

        foreach ($commitHashes as $commitHash) {
            $this->addCommitHash($commitHash);
        }

        return $this;
    }

    public function addCommitHash(CommitHash $commitHash): CommitHashCollection
    {
        $this->commitHashes[] = $commitHash;

        return $this;
    }
}
