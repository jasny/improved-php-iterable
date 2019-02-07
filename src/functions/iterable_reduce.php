<?php declare(strict_types=1);

namespace Improved;

/**
 * Reduce all elements to a single value using a callback.
 *
 * @param iterable  $iterable
 * @param callable  $callback
 * @param mixed     $initial
 * @return mixed
 */
function iterable_reduce(iterable $iterable, callable $callback, $initial = null)
{
    $result = $initial;

    foreach ($iterable as $key => $value) {
        $result = call_user_func($callback, $result, $value, $key);
    }

    return $result;
}
