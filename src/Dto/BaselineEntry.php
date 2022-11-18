<?php

declare(strict_types=1);

namespace App\Dto;

final class BaselineEntry
{
    public function __construct(
        private readonly string $message,
        private readonly int $count,
        private readonly string $path
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
