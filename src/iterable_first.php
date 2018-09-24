<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Get the first element.
 * Returns null if empty.
 *
 * @param iterable $iterable
 * @return mixed
 */
function iterable_first(iterable $iterable)
{
    foreach ($iterable as $value) {
        return $value;
    }

    return null;
}
