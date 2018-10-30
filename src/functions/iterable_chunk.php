<?php declare(strict_types=1);

namespace Improved;

/**
 * Divide iterable into chunks of specified size.
 *
 * @param iterable $iterable
 * @param int      $size
 * @return \Generator
 */
function iterable_chunk(iterable $iterable, int $size): \Generator
{
    $generate = function(\Iterator $iterator, int $size): \Generator {
        for ($i = 0; $i < $size && $iterator->valid(); $i++) {
            yield $iterator->key() => $iterator->current();

            $iterator->next();
        }
    };

    $iterator = iterable_to_iterator($iterable);
    $iterator->rewind();

    while ($iterator->valid()) {
        yield $generate($iterator, $size);
    }
}
