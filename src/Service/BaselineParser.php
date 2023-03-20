<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\BaselineEntryCollection;
use App\Dto\BaselineStatisticResult;
use App\Dto\BaselineStatisticResultCollection;
use App\Service\Analyze\EntryAnalyzer;
use App\Service\Parser\ParserInterface;

class BaselineParser
{
    /**
     * @param ParserInterface[]|iterable $baselineFileParser
     */
    public function __construct(
        private readonly iterable $baselineFileParser,
        private readonly EntryAnalyzer $entryAnalyzer
    ) {
    }

    public function getStatisticForFile(string $baselineFile, string $configurationFile): BaselineStatisticResultCollection
    {
        $collection = new BaselineStatisticResultCollection();

        $collection->addStatisticResult($this->getStatisticResult($baselineFile, $configurationFile));

        return $collection;
    }

    private function getStatisticResult(string $baselineFile, string $configurationFile): BaselineStatisticResult
    {
        $baselineEntryCollection = $this->parseFile($baselineFile, $configurationFile);

        return $this->entryAnalyzer->analyze($baselineEntryCollection);
    }

    public function parseFile(string $baselineFile, string $configurationFile): BaselineEntryCollection
    {
        foreach ($this->baselineFileParser as $baselineFileParser) {
            if (!$baselineFileParser->supports($baselineFile)) {
                continue;
            }

            return $baselineFileParser->parse($baselineFile, $configurationFile);
        }

        return new BaselineEntryCollection($baselineFile);
    }
}
