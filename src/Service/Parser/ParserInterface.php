<?php

declare(strict_types=1);

namespace App\Service\Parser;

use App\Dto\BaselineEntryCollection;

interface ParserInterface
{
    public function parse(string $fileName): BaselineEntryCollection;

    public function supports(string $fileName): bool;
}
