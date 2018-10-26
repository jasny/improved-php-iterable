<?php declare(strict_types=1);

namespace Improved;

/**
 * Get the first element of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $required  Throw RangeException instead of returning null for empty iterable
 * @return mixed
 */
function iterable_first(iterable $iterable, bool $required = false)
{
    foreach ($iterable as $value) {
        return $value;
    }

    if ($required) {
        throw new \RangeException("Unable to get first element; iterable is empty");
    }

    return null;
}
