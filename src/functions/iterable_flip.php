<?php

declare(strict_types=1);

namespace Improved;

use Generator;

/**
 * Use values as keys and visa versa.
 *
 * @param iterable<mixed> $iterable
 * @return Generator
 */
function iterable_flip(iterable $iterable): Generator
{
    foreach ($iterable as $key => $value) {
        yield $value => $key;
    }
}
