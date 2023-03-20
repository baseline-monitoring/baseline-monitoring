<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\Dto\BaselineEntryCollection;

interface ParserInterface
{
    public function parse(string $baselineFile, string $configurationFile): BaselineEntryCollection;

    public function supports(string $fileName): bool;
}
