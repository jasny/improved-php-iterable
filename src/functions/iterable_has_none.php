<?php

declare(strict_types=1);

namespace Improved;

/**
 * Check the no elements match the condition.
 *
 * @param iterable<mixed> $iterable
 * @param callable $matcher
 * @return bool
 */
function iterable_has_none(iterable $iterable, callable $matcher): bool
{
    foreach ($iterable as $key => $value) {
        if ((bool)call_user_func($matcher, $value, $key)) {
            return false;
        }
    }

    return true;
}
