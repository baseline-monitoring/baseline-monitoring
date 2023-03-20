<?php

declare(strict_types=1);

namespace App\Service\Analyze;

use App\Dto\BaselineEntryCollection;
use App\Dto\BaselineStatisticResult;

class EntryAnalyzer
{
    public function analyze(BaselineEntryCollection $baselineEntryCollection): BaselineStatisticResult
    {
        return new BaselineStatisticResult(
            $baselineEntryCollection->getFileName(),
            $baselineEntryCollection->getCount(),
            $baselineEntryCollection->getCommutativeCount(),
            $baselineEntryCollection->getVersion()
        );
    }
}
