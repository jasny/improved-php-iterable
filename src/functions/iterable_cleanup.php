<?php declare(strict_types=1);

namespace Improved;

/**
 * Filter out elements with null value and/or null key from iterator.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_cleanup(iterable $iterable): \Generator
{
    foreach ($iterable as $key => $value) {
        if (isset($key) && isset($value)) {
            yield $key => $value;
        }
    }
}
