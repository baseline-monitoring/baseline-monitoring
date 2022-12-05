<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Process\Exception\ProcessFailedException as SymfonyProcessFailedException;

class ProcessFailedException extends SymfonyProcessFailedException
{
}
