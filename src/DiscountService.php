<?php

declare(strict_types=1);

namespace App;

use DateTimeImmutable;
use DateTimeZone;

final class DiscountService
{
    public function getDiscountPercent(DateTimeImmutable $now): int
    {
        $local = $now->setTimezone(new DateTimeZone('Europe/Paris'));

        if ((int)$local->format('m') === 11 && (int)$local->format('N') === 5) {
            $nextFriday = $local->modify('+7 days');

            if ((int)$nextFriday->format('m') !== 11) {
                return 20;
            }
        }

        return 0;
    }
}
