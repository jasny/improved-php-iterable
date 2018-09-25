<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Get the last element of an iterable.
 * Returns null if the iterable is empty and an element is not required.
 *
 * @param iterable $iterable
 * @param bool     $required
 * @return mixed
 * @throws \RangeException  if the iterable is empty and an element is required
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
