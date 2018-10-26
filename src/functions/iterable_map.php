<?php declare(strict_types=1);

namespace Improved;

/**
 * Applies the callback to the elements of the iterator, replacing the value.
 *
 * @param iterable $iterable
 * @param callable $callback
 * @return \Generator
 */
function iterable_map(iterable $iterable, callable $callback): \Generator
{
    foreach ($iterable as $key => $value) {
        yield $key => call_user_func($callback, $value, $key);
    }
}
