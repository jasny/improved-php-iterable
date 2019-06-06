<?php /** @noinspection PhpVariableVariableInspection */

declare(strict_types=1);

namespace Improved;

/**
 * Deconstruct an iterable property/item for each element. The result is one element for each item in the iterable
 * property.
 *
 * @param iterable        $iterable      Iterable holding arrays or objects
 * @param int|string      $column
 * @param int|string|null $mapKey        The name of a new property to hold the array index of the element
 * @param bool            $preserveKeys  Preserve the keys of the iterable (will result in duplicate keys)
 * @return \Generator
 */
function iterable_unwind(iterable $iterable, $column, $mapKey = null, bool $preserveKeys = false): \Generator
{
    $counter = 0;

    $setArray = function ($element, $value, $key) use ($column, $mapKey) {
        return array_merge($element, [$column => $value], $mapKey === null ? [] : [$mapKey => $key]);
    };

    $setArrayAccess = function ($element, $value, $key) use ($column, $mapKey) {
        $copy = clone $element;

        $copy[$column] = $value;
        if ($mapKey !== null) {
            $copy[$mapKey] = $key;
        }

        return $copy;
    };

    $setObject = function ($element, $value, $key) use ($column, $mapKey) {
        $copy = clone $element;

        $copy->$column = $value;
        if ($mapKey !== null) {
            $copy->$mapKey = $key;
        }

        return $copy;
    };

    foreach ($iterable as $topKey => $element) {
        $set = null;
        $iterated = false;

        if (is_array($element)) {
            $value = $element[$column] ?? null;
            $set = $setArray;
        } elseif ($element instanceof \ArrayAccess) {
            $value = $element[$column] ?? null;
            $set = $setArrayAccess;
        } elseif (is_object($element) && !$element instanceof \DateTimeInterface) {
            $value = $element->$column ?? null;
            $set = $setObject;
        } else {
            $value = null;
        }

        if (!is_iterable($value) || $set === null) {
            yield ($preserveKeys ? $topKey : $counter++) => $element;
            continue;
        }

        foreach ($value as $key => $item) {
            $iterated = true;
            yield ($preserveKeys ? $topKey : $counter++) => $set($element, $item, $key);
        }

        if (!$iterated) {
            yield ($preserveKeys ? $topKey : $counter++) => $set($element, null, null);
        }
    }
}
