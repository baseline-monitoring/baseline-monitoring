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

    public function getParsedErrors(string $fileName): BaselineEntryCollection
    {
        return $this->parseFile($fileName);
    }

    /**
     * @param string[] $baselineFiles
     */
    public function getStatisticsForFiles(array $baselineFiles): BaselineStatisticResultCollection
    {
        $collection = new BaselineStatisticResultCollection();

        foreach ($baselineFiles as $baselineFile) {
            $collection->addStatisticResult($this->getStatisticResult($baselineFile));
        }

        return $collection;
    }

    private function getStatisticResult(string $fileName): BaselineStatisticResult
    {
        $baselineEntryCollection = $this->parseFile($fileName);

        return $this->entryAnalyzer->analyze($baselineEntryCollection);
    }

    public function parseFile(string $fileName): BaselineEntryCollection
    {
        foreach ($this->baselineFileParser as $baselineFileParser) {
            if (!$baselineFileParser->supports($fileName)) {
                continue;
            }

            return $baselineFileParser->parse($fileName);
        }

        return new BaselineEntryCollection($fileName);
    }
}
