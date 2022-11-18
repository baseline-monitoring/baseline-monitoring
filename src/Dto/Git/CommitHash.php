<?php

namespace App\Dto\Git;

use DateTimeImmutable;

final class CommitHash
{
    public function __construct(
        private readonly string $hash,
        private readonly DateTimeImmutable $commitDate
    ) {
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getCommitDate(): DateTimeImmutable
    {
        return $this->commitDate;
    }
}
