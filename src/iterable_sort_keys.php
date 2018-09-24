<?php

declare(strict_types=1);

namespace Jasny;

use function Jasny\expect_type;

/**
 * Sort all elements of an iterator based on the key.
 *
 * @param iterable     $iterable
 * @param callable|int $compare   SORT_* flags as binary set or callback comparator function
 * @return \Generator
 */
function iterable_sort_keys(iterable $iterable, $compare = \SORT_REGULAR): \Generator
{
    expect_type(
        $compare,
        ['callable', 'int'],
        \TypeError::class,
        "Expected comparator to be a callable or integer, %s given"
    );

    $comparator = is_int($compare) ? null : $compare;
    $flags = is_int($compare) ? $compare : \SORT_REGULAR;

    $keys = [];
    $values = [];

    foreach ($iterable as $key => $value) {
        $keys[] = $key;
        $values[] = $value;
    }

    unset($iterable);

    isset($comparator)
        ? uasort($keys, $comparator)
        : asort($keys, $flags);

    foreach ($keys as $index => $key) {
        yield $key => $values[$index];
    }
}