<?php declare(strict_types=1);

namespace Improved;

/**
 * Walk through all sub-iterables (array, Iterator or IteratorAggregate) and combine them.
 *
 * @param iterable $iterable
 * @param bool     $preserveKeys
 * @return \Generator
 */
function iterable_flatten(iterable $iterable, bool $preserveKeys = false): \Generator
{
    $counter = 0;

    foreach ($iterable as $topKey => $element) {
        if (!is_iterable($element)) {
            yield ($preserveKeys ? $topKey : $counter++) => $element;
            continue;
        }

        foreach ($element as $key => $item) {
            yield ($preserveKeys ? $key : $counter++) => $item;
        }
    }
}
