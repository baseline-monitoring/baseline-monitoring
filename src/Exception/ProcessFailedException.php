<?php

namespace App\Exception;

use Symfony\Component\Process\Exception\ProcessFailedException as SymfonyProcessFailedException;

class ProcessFailedException extends SymfonyProcessFailedException
{
}
