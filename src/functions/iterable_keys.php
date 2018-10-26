<?php declare(strict_types=1);

namespace Improved;

/**
 * Use the keys as values.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_keys(iterable $iterable): \Generator
{
    foreach ($iterable as $key => $value) {
        yield $key;
    }
}
