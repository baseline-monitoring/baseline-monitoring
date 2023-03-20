<?php

declare(strict_types=1);

namespace App\Dto;

final class BaselineEntryCollection
{
    /**
     * @var BaselineEntry[]
     */
    private array $baselineEntries = [];

    private string|null $version = null;

    /**
     * @param BaselineEntry[] $baselineEntries
     */
    public function __construct(
        private readonly string $fileName,
        array $baselineEntries = []
    ) {
        $this->setBaselineEntries($baselineEntries);
    }

    /**
     * @return BaselineEntry[]
     */
    public function getBaselineEntries(): array
    {
        return $this->baselineEntries;
    }

    /**
     * @param BaselineEntry[] $baselineEntries
     */
    public function setBaselineEntries(array $baselineEntries): BaselineEntryCollection
    {
        $this->baselineEntries = [];

        foreach ($baselineEntries as $baselineEntry) {
            $this->addBaselineEntry($baselineEntry);
        }

        return $this;
    }

    public function addBaselineEntry(BaselineEntry $baselineEntry): BaselineEntryCollection
    {
        $this->baselineEntries[] = $baselineEntry;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getCount(): int
    {
        return count($this->baselineEntries);
    }

    public function getCommutativeCount(): int
    {
        return array_sum(array_map(static fn (BaselineEntry $baselineEntry) => $baselineEntry->getCount(), $this->baselineEntries));
    }

    public function sortByCount(): BaselineEntryCollection
    {
        usort($this->baselineEntries, static fn (BaselineEntry $first, BaselineEntry $second): int => $second->getCount() <=> $first->getCount());

        return $this;
    }

    public function getFirstEntries(int $numberOfEntries): BaselineEntryCollection
    {
        return new BaselineEntryCollection($this->fileName, array_slice($this->baselineEntries, 0, $numberOfEntries));
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): BaselineEntryCollection
    {
        $this->version = $version;

        return $this;
    }
}
