<?php declare(strict_types=1);

namespace Improved;

/**
 * Get the maximal element according to a given comparator.
 *
 * @param iterable      $iterable
 * @param callable|null $compare
 * @return mixed
 */
function iterable_max(iterable $iterable, callable $compare = null)
{
    $first = true;
    $max = null;

    if (!isset($compare)) {
        foreach ($iterable as $value) {
            $max = (!isset($max) || $value > $max) ? $value : $max;
        }
    } else {
        foreach ($iterable as $value) {
            $max = ($first || call_user_func($compare, $max, $value) < 0) ? $value : $max;
            $first = false;
        }
    }

    return $max;
}
