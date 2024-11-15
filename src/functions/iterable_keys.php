<?php

declare(strict_types=1);

namespace Improved;

use Generator;

/**
 * Use the keys as values.
 *
 * @param iterable<mixed> $iterable
 * @return Generator
 */
function iterable_keys(iterable $iterable): Generator
{
    foreach ($iterable as $key => $value) {
        yield $key;
    }
}
