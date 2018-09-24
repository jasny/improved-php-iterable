<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Get the last element of an iterable.
 * Returns null if the iterable empty.
 *
 * @param iterable $iterable
 * @return mixed
 */
function iterable_last(iterable $iterable)
{
    if (is_array($iterable)) {
        return end($iterable);
    }

    $last = null;

    foreach ($iterable as $value) {
        $last = $value;
    }

    return $last;
}
