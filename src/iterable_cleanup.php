<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Filter out null elements from iterator.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_cleanup(iterable $iterable): \Generator
{
    foreach ($iterable as $key => $value) {
        if (isset($value)) {
            yield $key => $value;
        }
    }
}
