<?php declare(strict_types=1);

namespace Improved;

/**
 * Get the minimal element according to a given comparator.
 *
 * @param iterable      $iterable
 * @param callable|null $compare
 * @return mixed
 */
function iterable_min(iterable $iterable, callable $compare = null)
{
    $first = true;
    $min = null;

    if (!isset($compare)) {
        foreach ($iterable as $value) {
            $min = (!isset($min) || $value < $min) ? $value : $min;
        }
    } else {
        foreach ($iterable as $value) {
            $min = ($first || call_user_func($compare, $min, $value) > 0) ? $value : $min;
            $first = false;
        }
    }

    return $min;
}
