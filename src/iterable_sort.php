<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Sort all elements of an iterator.
 *
 * @param iterable     $iterable
 * @param callable|int $compare       SORT_* flags as binary set or callback comparator function
 * @param bool         $preserveKeys
 * @return iterable
 */
function iterable_sort(iterable $iterable, $compare = \SORT_REGULAR, bool $preserveKeys = false): iterable
{
    expect_type($compare, ['callable', 'int'], "Expected comparator to be a callable or integer, %s given");

    $comparator = is_int($compare) ? null : $compare;
    $flags = is_int($compare) ? $compare : \SORT_REGULAR;

    if ($preserveKeys) {
        $keys = [];
        $values = [];

        foreach ($iterable as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }
    } else {
        $values = is_array($iterable) ? $iterable : iterator_to_array($iterable, false);
    }

    if (isset($comparator)) {
        uasort($values, $comparator);
    } else {
        asort($values, $flags);
    }

    if (!isset($keys)) {
        return $values;
    }

    foreach ($values as $index => $value) {
        yield $keys[$index] => $value;
    }
}
