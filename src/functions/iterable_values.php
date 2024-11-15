<?php

declare(strict_types=1);

namespace Improved;

use Generator;

/**
 * Use the values, drop the keys.
 *
 * @param iterable<mixed> $iterable
 * @return Generator
 */
function iterable_values(iterable $iterable): Generator
{
    foreach ($iterable as $value) {
        yield $value;
    }
}
