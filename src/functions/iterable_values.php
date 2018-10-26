<?php declare(strict_types=1);

namespace Improved;

/**
 * Use the values, drop the keys.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_values(iterable $iterable): \Generator
{
    foreach ($iterable as $value) {
        yield $value;
    }
}
