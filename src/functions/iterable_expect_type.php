<?php

declare(strict_types=1);

namespace Improved;

use Generator;
use Throwable;
use UnexpectedValueException;

/**
 * Check the type of iterator elements.
 *
 * @param iterable               $iterable
 * @param string|string[]        $type
 * @param string|Throwable|null $error      Class + message or only message
 * @return Generator
 *
 * @deprecated Use {@see iterable_expect_type()} instead
 */
function iterable_expect_type(iterable $iterable, string|array $type, string|Throwable|null $error = null): Generator
{
    if (!$error instanceof Throwable) {
        $error = new UnexpectedValueException($error ?? "Expected all elements to be of type %3\$s, %1\$s given");
    }

    return iterable_type_check($iterable, $type, $error);
}
