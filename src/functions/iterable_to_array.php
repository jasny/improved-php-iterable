<?php

declare(strict_types=1);

namespace Ipl;

use function Jasny\get_type_description;

/**
 * Convert any iterable to an array.
 *
 * @param array|\Traversable $iterable
 * @param bool|null          $preserveKeys  NULL means don't care
 * @return array
 */
function iterable_to_array(iterable $iterable, ?bool $preserveKeys = null): array
{
    switch (true) {
        case is_array($iterable):
            break;
        case is_object($iterable) && method_exists($iterable, 'toArray'):
            $iterable = $iterable->toArray();
            break;
        case is_object($iterable) && method_exists($iterable, 'getArrayCopy'):
            $iterable = $iterable->getArrayCopy();
            break;

        case $iterable instanceof \Traversable:
            return iterator_to_array($iterable, $preserveKeys === true);

        default:
            // @codeCoverageIgnoreStart
            $type = get_type_description($iterable);
            throw new \InvalidArgumentException("Unknown iterable: $type");
            // @codeCoverageIgnoreEnd
    }

    return $preserveKeys === false ? array_values($iterable) : $iterable;
}
