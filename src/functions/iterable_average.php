<?php declare(strict_types=1);

namespace Improved;

/**
 * Return the arithmetic mean.
 * If no elements are present, the result is NAN.
 *
 * @param iterable $iterable
 * @return float
 */
function iterable_average(iterable $iterable): float
{
    $count = 0;
    $sum = 0;

    foreach ($iterable as $item) {
        $count++;
        $sum += $item;
    }

    return $count == 0 ? \NAN : ($sum / $count);
}
