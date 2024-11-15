<?php

declare(strict_types=1);

namespace Improved;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use IteratorIterator;
use Traversable;

/**
 * Convert any iterable to an Iterator object.
 *
 * @param array|Traversable $iterable
 * @return Iterator
 */
function iterable_to_iterator(iterable $iterable): Iterator
{
    if (is_array($iterable)) {
        return new ArrayIterator($iterable);
    }

    if ($iterable instanceof IteratorAggregate) {
        $iterable = $iterable->getIterator();
    }

    if (!$iterable instanceof Iterator) {
        $iterable = new IteratorIterator($iterable);
    }

    return $iterable;
}
