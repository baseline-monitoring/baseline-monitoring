<?php

declare(strict_types=1);

namespace App\Dto;

final class BaselineEntry
{
    public function __construct(
        private string $message,
        private int $count,
        private string $path
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
