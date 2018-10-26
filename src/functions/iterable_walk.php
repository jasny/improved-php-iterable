<?php declare(strict_types=1);

namespace Improved;

/**
 * Traverse over the iterator, not capturing the values.
 * This is particularly useful after `iterable_apply()`.
 *
 * @param iterable $iterable
 * @return void
 */
function iterable_walk(iterable $iterable): void
{
    if (is_array($iterable)) {
        return; // No point walking over an array
    }

    /** @noinspection ALL */
    foreach ($iterable as $_) {
        // nop
    }
}
