<?php declare(strict_types=1);

namespace Improved;

/**
 * Get the last element of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $required  Throw RangeException instead of returning null for empty iterable
 * @return mixed
 */
function iterable_last(iterable $iterable, bool $required = false)
{
    if (is_array($iterable) && $iterable !== []) {
        return end($iterable);
    }

    $last = null;
    $empty = true; // because $last can be any value including null.

    foreach ($iterable as $value) {
        $last = $value;
        $empty = false;
    }

    if ($empty && $required) {
        throw new \RangeException("Unable to get last element; iterable is empty");
    }

    return $last;
}
