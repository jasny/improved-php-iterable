<?php declare(strict_types=1);

namespace Improved;

/**
 * Count element of an iterable.
 *
 * @param iterable $iterable
 * @return int
 */
function iterable_count(iterable $iterable): int
{
    /** @var array|\Traversable $iterable */
    return is_array($iterable) || $iterable instanceof \Countable ? count($iterable) : iterator_count($iterable);
}
