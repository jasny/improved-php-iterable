<?php /** @noinspection PhpVariableVariableInspection */

declare(strict_types=1);

namespace Improved;

/**
 * Project each element of an iterator to an associated (or numeric) array.
 *
 * @param iterable $iterable
 * @param array    $mapping
 * @return \Generator
 */
function iterable_project(iterable $iterable, array $mapping): \Generator
{
    foreach ($iterable as $key => $value) {
        $projected = [];

        if (is_array($value) || $value instanceof \ArrayAccess) {
            foreach ($mapping as $to => $from) {
                $projected[$to] = $value[$from] ?? null;
            }
        } elseif (is_object($value) && !$value instanceof \DateTimeInterface) {
            foreach ($mapping as $to => $from) {
                $projected[$to] = $value->$from ?? null;
            }
        } else {
            $projected = array_fill_keys(array_keys($mapping), null);
        }

        yield $key => $projected;
    }
}
