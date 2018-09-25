<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Convert any iterable to an array.
 *
 * @param array|\Traversable $iterable
 * @return array
 */
function iterable_to_array(iterable $iterable): array
{
    switch (true) {
        case is_array($iterable):
            return $iterable;
        case is_object($iterable) && method_exists($iterable, 'toArray'):
            return $iterable->toArray();
        case is_object($iterable) && method_exists($iterable, 'getArrayCopy'):
            return $iterable->getArrayCopy();
        case $iterable instanceof \Traversable:
            return iterator_to_array($iterable, true);
    }

    throw new \InvalidArgumentException("Unknown iterable: " . gettype($iterable)); // @codeCoverageIgnore
}
