<?php declare(strict_types=1);

namespace Improved;

/**
 * Convert any iterable to a Traversable object (Iterator or IteratorAggregate).
 *
 * @param array|\Traversable $iterable
 * @return \Traversable
 */
function iterable_to_traversable(iterable $iterable): \Traversable
{
    return is_array($iterable) ? new \ArrayIterator($iterable) : $iterable;
}
