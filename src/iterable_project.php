<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Project each element of an iterator to an associated (or numeric) array.
 * Scalar, null and resource elements are untouched.
 *
 * @param iterable $iterable
 * @param array $mapping
 * @return \Generator
 */
function iterable_project(iterable $iterable, array $mapping): \Generator
{
    foreach ($iterable as $key => $value) {
        $projected = [];

        if (is_array($value) || $value instanceof \ArrayAccess) {
            foreach ($mapping as $to => $from) {
                $projected[$to] = $element[$from] ?? null;
            }
        } elseif (is_object($value) && !$value instanceof \DateTimeInterface) {
            foreach ($mapping as $to => $from) {
                $projected[$to] = $element->$from ?? null;
            }
        } else {
            $projected = $value;
        }

        yield $key => $projected;
    }
}
