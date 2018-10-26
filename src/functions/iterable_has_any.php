<?php declare(strict_types=1);

namespace Improved;

/**
 * Check if any element matches the condition.
 *
 * @param iterable $iterable
 * @param callable $matcher
 * @return bool
 */
function iterable_has_any(iterable $iterable, callable $matcher): bool
{
    foreach ($iterable as $key => $value) {
        if ((bool)call_user_func($matcher, $value, $key)) {
            return true;
        }
    }

    return false;
}
