<?php declare(strict_types=1);

namespace Improved;

/**
 * Reverse order of elements of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $preserveKeys
 * @return \Generator
 */
function iterable_reverse(iterable $iterable, bool $preserveKeys = false): \Generator
{
    if (is_array($iterable)) {
        $values = array_reverse($iterable, $preserveKeys);
    } elseif (!$preserveKeys) {
        $values = [];

        foreach ($iterable as $key => $value) {
            array_unshift($values, $value);
        }
    } else {
        /** @var array $keys */
        $keys = [];
        $values = [];

        foreach ($iterable as $key => $value) {
            array_unshift($keys, $key);
            array_unshift($values, $value);
        }
    }

    unset($iterable);

    foreach ($values as $index => $value) {
        $key = isset($keys) ? $keys[$index] : $index;
        yield $key => $value;
    }
}
