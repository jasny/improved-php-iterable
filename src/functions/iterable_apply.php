<?php

declare(strict_types=1);

namespace Improved;

use Generator;

/**
 * Apply a callback to each element.
 *
 * @param iterable<mixed> $iterable
 * @param callable $callback
 * @return Generator
 */
function iterable_apply(iterable $iterable, callable $callback): Generator
{
    foreach ($iterable as $key => $value) {
        call_user_func($callback, $value, $key);

        yield $key => $value;
    }
}
