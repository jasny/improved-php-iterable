<?php /** @noinspection PhpVariableVariableInspection */ declare(strict_types=1);

namespace Improved;

/**
 * Reshape each element of an iterator, adding or removing properties or keys.
 *
 * @param iterable $iterable
 * @param array    $columns   Columns to show or hide
 * @return \Generator
 */
function iterable_reshape(iterable $iterable, array $columns): \Generator
{
    $change = array_filter($columns, function ($keep) {
        return !is_bool($keep);
    });

    $remove = array_fill_keys(array_diff(array_keys(array_filter($columns, function ($keep) {
        return $keep !== true;
    })), array_values($columns)), null);

    $shapeArray = function (&$value) use ($change, $remove): void {
        foreach ($change as $from => $to) {
            if (isset($value[$from])) {
                $value[$to] = $value[$from];
            }
        }

        foreach ($remove as $key => $null) {
            if (isset($value[$key])) {
                unset($value[$key]);
            }
        }
    };

    $shapeObject = function ($value) use ($change, $remove): void {
        foreach ($change as $from => $to) {
            if (isset($value->$from)) {
                $value->$to = $value->$from;
            }
        }

        foreach ($remove as $key => $null) {
            unset($value->$key);
        }
    };

    foreach ($iterable as $key => $value) {
        if (is_array($value) || $value instanceof \ArrayAccess) {
            $shapeArray($value);
        } elseif (is_object($value) && !$value instanceof \DateTimeInterface) {
            $shapeObject($value);
        }

        yield $key => $value;
    }
}
