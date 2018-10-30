<?php declare(strict_types=1);

namespace Improved;

/**
 * Check the type of iterator elements.
 * @deprecated
 *
 * @param iterable               $iterable
 * @param string|string[]        $type
 * @param string|\Throwable|null $error      Class + message or only message
 * @return \Generator
 */
function iterable_expect_type(iterable $iterable, $type, $error = null): \Generator
{
    if (!$error instanceof \Throwable) {
        $error = new \UnexpectedValueException($error ?? "Expected all elements to be of type %3\$s, %1\$s given");
    }

    return iterable_type_check($iterable, $type, $error);
}
