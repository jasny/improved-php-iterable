<?php declare(strict_types=1);

namespace Improved;

/**
 * Use values as keys and visa versa.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_flip(iterable $iterable): \Generator
{
    foreach ($iterable as $key => $value) {
        yield $value => $key;
    }
}
