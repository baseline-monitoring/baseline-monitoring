<?php

declare(strict_types=1);

namespace App\Service\Util;

use DateTimeImmutable;
use DateTimeZone;

class DateUtil
{
    public static function createDateTimeImmutable(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
