<?php

declare(strict_types=1);

namespace Improved;

use function Jasny\expect_type;
use function Jasny\array_join_pretty;

/**
 * Check the type of iterator elements.
 *
 * @param iterable               $iterable
 * @param string|string[]        $type
 * @param string|\Throwable|null $error      Class + message or only message
 * @return \Generator
 */
function iterable_expect_type(iterable $iterable, $type, $error = null): \Generator
{
    expect_type($type, ['string', 'array'], \TypeError::class, "Expected type to be string or string[], %s given");
    expect_type(
        $error,
        ['string', \Throwable::class, 'null'],
        \TypeError::class,
        "Expected type to be %2\$s, %1\$s given"
    );

    $throwable = $error instanceof \Throwable ? get_class($error) : \UnexpectedValueException::class;
    $message = ($error instanceof \Throwable ? $error->getMessage() : $error) ?:
        'Expected all elements to be of type %2$s, %1$s given';

    foreach ($iterable as $key => $value) {
        expect_type($value, $type, $throwable, $message);

        yield $key => $value;
    }
}
