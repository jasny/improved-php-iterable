<?php declare(strict_types=1);

namespace Improved;

/**
 * Get a limited subset of the elements using an offset and (optionally) a limit.
 *
 * @param iterable $iterable
 * @param int      $offset
 * @param int|null $limit
 * @return \Generator
 */
function iterable_slice(iterable $iterable, int $offset, ?int $limit = null): \Generator
{
    $counter = 0;
    $end = isset($limit) ? ($offset + $limit) : PHP_INT_MAX;

    foreach ($iterable as $key => $value) {
        if ($counter === $end) {
            return;
        }

        if ($counter >= $offset) {
            yield $key => $value;
        }

        $counter++;
    }
}
