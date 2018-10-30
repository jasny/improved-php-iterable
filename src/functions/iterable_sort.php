<?php declare(strict_types=1);

namespace Improved;

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
    type_check($compare, ['callable', 'int'], new \TypeError("Expected compare to be callable or integer, %s given"));

    $comparator = is_int($compare) ? null : $compare;
    $flags = is_int($compare) ? $compare : 0;

    ['keys' => $keys, 'values' => $values] = $preserveKeys
        ? iterable_separate($iterable)
        : ['keys' => null, 'values' => iterable_to_array($iterable, false)];

    if (!is_array($values)) {
        throw new \UnexpectedValueException("Values should be an array"); // @codeCoverageIgnore
    }

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
