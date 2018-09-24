<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Convert any iterable to an array.
 *
 * @param iterable $iterable
 * @return array
 */
function iterable_to_array(iterable $iterable): array
{
    switch (true) {
        case is_array($iterable):
            return $iterable;
        case method_exists($iterable, 'toArray'):
            return $iterable->toArray();
        case method_exists($iterable, 'getArrayCopy'):
            return $iterable->getArrayCopy();
        default:
            return iterator_to_array($iterable, true);
    }
}
