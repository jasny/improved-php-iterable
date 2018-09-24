<?php

declare(strict_types=1);

namespace Jasny;

use Jasny\Iterator\CombineIterator;

/**
 * Reverse order of elements of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $preserveKeys
 * @return iterable
 */
function iterable_reverse(iterable $iterable, bool $preserveKeys = false): iterable
{
    if (is_array($iterable) || !$preserveKeys) {
        $array = iterable_to_array($iterable);

        return array_reverse($array, $preserveKeys);
    }

    $keys = [];
    $values = [];

    foreach ($iterable as $key => $value) {
        array_unshift($keys, $key);
        array_unshift($values, $value);
    }

    return new CombineIterator($keys, $values);
}
