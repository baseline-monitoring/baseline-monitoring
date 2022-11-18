<?php

declare(strict_types=1);

namespace App\Dto;

final class BaselineStatisticResult
{
    public function __construct(
        private readonly string $fileName,
        private readonly int $uniqueErrors,
        private readonly int $commutativeErrors,
        private readonly ?string $version = null
    ) {
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getUniqueErrors(): int
    {
        return $this->uniqueErrors;
    }

    public function getCommutativeErrors(): int
    {
        return $this->commutativeErrors;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }
}
