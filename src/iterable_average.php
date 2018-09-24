<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Return the arithmetic mean.
 *
 * @return float
 * @throws \UnexpectedValueException if not all values are integers or floats
 */
function iterable_average(iterable $iterable): float
{
    $count = 0;
    $sum = 0;

    foreach ($iterable as $item) {
        expect_type($item, ['int', 'float'],\UnexpectedValueException::class);

        $count++;
        $sum += $item;
    }

    return $count == 0 ? \NAN : ($sum / $count);
}
