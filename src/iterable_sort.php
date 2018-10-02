<?php

declare(strict_types=1);

namespace Ipl;

use function Jasny\expect_type;

/**
 * Sort all elements of an iterator.
 *
 * @param iterable     $iterable
 * @param callable|int $compare       SORT_* flag or callback comparator function
 * @param bool         $preserveKeys
 * @return \Generator
 */
function iterable_sort(iterable $iterable, $compare, bool $preserveKeys = false): \Generator
{
    expect_type(
        $compare,
        ['callable', 'int'],
        \TypeError::class,
        "Expected compare to be a callable or integer, %s given"
    );

    $comparator = is_int($compare) ? null : $compare;
    $flags = is_int($compare) ? $compare : 0;

    ['keys' => $keys, 'values' => $values] = $preserveKeys
        ? iterable_separate($iterable)
        : ['keys' => null, 'values' => is_array($iterable) ? $iterable : iterator_to_array($iterable, false)];

    isset($comparator)
        ? uasort($values, $comparator)
        : asort($values, $flags);

    $counter = 0;
    unset($iterable);

    foreach ($values as $index => $value) {
        $key = isset($keys) ? $keys[$index] : $counter++;
        yield $key => $value;
    }
}
