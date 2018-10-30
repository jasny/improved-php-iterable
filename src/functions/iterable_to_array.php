<?php declare(strict_types=1);

namespace Improved;

use Improved\IteratorPipeline\Pipeline;

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
        case is_object($iterable) && !$iterable instanceof Pipeline && method_exists($iterable, 'toArray'):
            $iterable = $iterable->toArray();
            break;
        case is_object($iterable) && method_exists($iterable, 'getArrayCopy'):
            /** @var \ArrayObject $iterable  Duck typing */
            $iterable = $iterable->getArrayCopy();
            break;

        case $iterable instanceof \IteratorAggregate:
            return iterable_to_array($iterable->getIterator(), $preserveKeys); // recursion
        case $iterable instanceof \Traversable:
            return iterator_to_array($iterable, $preserveKeys === true);

        default:
            // @codeCoverageIgnoreStart
            throw new \InvalidArgumentException("Unknown iterable: " . type_describe($iterable));
            // @codeCoverageIgnoreEnd
    }

    return $preserveKeys === false ? array_values($iterable) : $iterable;
}
