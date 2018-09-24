<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Count iterable.
 *
 * @return int
 */
function iterable_count(iterable $iterable): int
{
    return is_array($iterable) || $iterable instanceof \Countable ? count($iterable) : iterator_count($iterable);
}
