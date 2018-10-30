<?php declare(strict_types=1);

namespace Improved;

/**
 * Sort all elements of an iterator based on the key.
 *
 * @param iterable     $iterable
 * @param callable|int $compare   SORT_* flag or callback comparator function
 * @return \Generator
 */
function iterable_sort_keys(iterable $iterable, $compare): \Generator
{
    type_check($compare, ['callable', 'int'], new \TypeError("Expected compare to be callable or integer, %s given"));

    $comparator = is_int($compare) ? null : $compare;
    $flags = is_int($compare) ? $compare : 0;

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
