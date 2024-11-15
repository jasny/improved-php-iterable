<?php

declare(strict_types=1);

namespace Improved;

use Generator;

/**
 * Filter elements using callback
 *
 * @param iterable<mixed> $iterable
 * @param callable $matcher
 * @return Generator
 */
function iterable_filter(iterable $iterable, callable $matcher): Generator
{
    foreach ($iterable as $key => $value) {
        if ((bool)call_user_func($matcher, $value, $key)) {
            yield $key => $value;
        }
    }
}
