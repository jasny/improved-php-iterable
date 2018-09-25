<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Get the first element of an iterable.
 * Returns null if the iterable is empty and an element is not required.
 *
 * @param iterable $iterable
 * @param bool     $required
 * @return mixed
 * @throws \RangeException  if the iterable is empty and an element is required
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
