<?php /** @noinspection PhpVariableVariableInspection */ declare(strict_types=1);

namespace Improved;

/**
 * Get a key and/or value of each element of the iterable.
 *
 * The elements need to be objects or arrays.
 * For scalar elements or if the property/index doesn't exist, the value and/or key will be null.
 *
 * @param iterable $iterable
 * @param mixed|null $valueColumn  Value property/index, null for complete value
 * @param mixed|null $keyColumn    Key property/index, null to keep current keys
 * @return \Generator
 */
function iterable_column(iterable $iterable, $valueColumn, $keyColumn = null): \Generator
{
    foreach ($iterable as $key => $value) {
        switch (true) {
            case is_array($value) || (is_object($value) && $value instanceof \ArrayAccess):
                $key = isset($keyColumn) ? ($value[$keyColumn] ?? null) : $key;
                $value = isset($valueColumn) ? ($value[$valueColumn] ?? null) : $value;
                break;
            case is_object($value) && !$value instanceof \DateTimeInterface:
                $key = isset($keyColumn) ? ($value->$keyColumn ?? null) : $key;
                $value = isset($valueColumn) ? ($value->$valueColumn ?? null) : $value;
                break;
            default:
                $key = isset($keyColumn) ? null : $key;
                $value = isset($valueColumn) ? null : $value;
                break;
        }

        yield $key => $value;
    }
}
