<?php declare(strict_types=1);

namespace Improved;

/**
 * Get both keys and values of iterable in separate arrays.
 *
 * @param iterable $iterable
 * @return array[]  ['keys' => array, 'values' => array]
 */
function iterable_separate(iterable $iterable): array
{
    if (is_array($iterable)) {
        return ['keys' => array_keys($iterable), 'values' => array_values($iterable)];
    }

    $keys = [];
    $values = [];

    foreach ($iterable as $key => $value) {
        $keys[] = $key;
        $values[] = $value;
    }

    return compact('keys', 'values');
}
