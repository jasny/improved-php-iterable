<?php declare(strict_types=1);

namespace Improved;

/**
 * Calculate the sum of all numbers.
 * If no elements are present, the result is 0.
 *
 * @param iterable $iterable
 * @return int|float
 */
function iterable_sum(iterable $iterable)
{
    if (is_array($iterable)) {
        return array_sum($iterable);
    }

    $sum = 0;

    foreach ($iterable as $item) {
        $sum += $item;
    }

    return $sum;
}
