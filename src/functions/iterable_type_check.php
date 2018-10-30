<?php declare(strict_types=1);

namespace Improved;

/**
 * Validate that a value has a specific type.
 *
 * @param iterable        $iterable
 * @param string|string[] $type
 * @param \Throwable|null $throwable
 * @return \Generator
 */
function iterable_type_check(iterable $iterable, $type, ?\Throwable $throwable = null): \Generator
{
    $msg = 'Expected %3$s, %1$s given; index %2$s';

    foreach ($iterable as $key => $var) {
        if (!type_is($var, $type)) {
            /** @var \TypeError $error */
            $error = Internal\type_check_error($var, $type, $throwable, $msg, [type_describe($key, true)]);
            throw $error;
        }

        yield $key => $var;
    }
}
