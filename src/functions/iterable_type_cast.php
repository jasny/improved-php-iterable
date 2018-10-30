<?php declare(strict_types=1);

namespace Improved;

/**
 * Cast a value to the specific type or throw an error.
 *
 * @param iterable        $iterable
 * @param string          $type
 * @param \Throwable|null $throwable
 * @return \Generator
 */
function iterable_type_cast(iterable $iterable, $type, $throwable = null): \Generator
{
    $msg = 'Unable to cast to %3$s, %1$s given; index %2$s';

    foreach ($iterable as $key => $var) {
        $valid = type_is($var, $type);
        $casted = $valid ? $var : Internal\type_cast_var($var, $type);

        if (!$valid && !isset($casted)) {
            /** @var \TypeError $error */
            $error = Internal\type_check_error($var, $type, $throwable, $msg, [type_describe($key, true)]);
            throw $error;
        }

        yield $key => $casted;
    }
}
