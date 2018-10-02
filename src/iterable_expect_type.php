<?php

declare(strict_types=1);

namespace Ipl;

use function Jasny\expect_type;
use function Jasny\array_join_pretty;

/**
 * Check the type of iterator elements.
 *
 * @param iterable        $iterable
 * @param string|string[] $type
 * @param string          $throwable  Class name
 * @param string          $message
 * @return \Generator
 */
function iterable_expect_type(
    iterable $iterable,
    $type,
    string $throwable = \UnexpectedValueException::class,
    string $message = null
): \Generator {
    expect_type($type, ['string', 'array'], \TypeError::class, "Expected type to be a string or string[], %s given");

    foreach ($iterable as $key => $value) {
        expect_type($value, $type, $throwable, $message ?? 'Expected all elements to be of type %2$s, %1$s given');

        yield $key => $value;
    }
}
