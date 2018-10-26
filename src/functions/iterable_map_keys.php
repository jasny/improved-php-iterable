<?php declare(strict_types=1);

namespace Improved;

/**
 * Replace the keys of an iterable using a callback.
 *
 * @param iterable $iterable
 * @param callable $callback
 * @return \Generator
 */
function iterable_map_keys(iterable $iterable, callable $callback): \Generator
{
    foreach ($iterable as $key => $value) {
        yield call_user_func($callback, $value, $key) => $value;
    }
}
