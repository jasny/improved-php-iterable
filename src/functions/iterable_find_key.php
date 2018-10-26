<?php declare(strict_types=1);

namespace Improved;

/**
 * Get the first element that matches a condition and return the key.
 * Returns null if no element is found.
 *
 * @param iterable $iterable
 * @param callable $matcher
 * @return mixed
 */
function iterable_find_key(iterable $iterable, callable $matcher)
{
    foreach ($iterable as $key => $value) {
        if ((bool)call_user_func($matcher, $value, $key)) {
            return $key;
        }
    }

    return null;
}
