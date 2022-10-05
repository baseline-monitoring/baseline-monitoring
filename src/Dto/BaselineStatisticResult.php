<?php

declare(strict_types=1);

namespace App\Dto;

final class BaselineStatisticResult
{
    public function __construct(
        private string $fileName,
        private int $uniqueErrors,
        private int $commutativeErrors
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
}
