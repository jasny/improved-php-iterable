<?php

declare(strict_types=1);

namespace Jasny;

use function Jasny\expect_type;

/**
 * Check the type of iterator elements.
 *
 * @param iterable        $iterable
 * @param string|string[] $type
 * @param string          $throwable  Class name
 * @param string          $message
 * @return \Generator
 * @throws \Throwable if type doesn't match
 */
function iterable_expect_type(
    iterable $iterable,
    $type,
    string $throwable = \UnexpectedValueException::class,
    string $message = null
): \Generator {
    expect_type($type, ['string', 'array'], "Expected type to be a string or string[], %s given");

    foreach ($iterable as $key => $value) {
        expect_type($value, $type, $throwable, $message);

        yield $key => $value;
    }
}
