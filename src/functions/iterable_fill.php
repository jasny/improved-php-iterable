<?php

declare(strict_types=1);

namespace Improved;

use Generator;

/**
 * Set all values of the iterable.
 *
 * @param iterable<mixed> $iterable
 * @param mixed    $value
 * @return Generator
 */
function iterable_fill(iterable $iterable, $value): Generator
{
    foreach ($iterable as $key => $_) {
        yield $key => $value;
    }
}
