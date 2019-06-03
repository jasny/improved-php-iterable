<?php declare(strict_types=1);

namespace Improved;

/**
 * Get elements after a match is found.
 *
 * @param iterable $iterable
 * @param callable $matcher
 * @param bool     $including
 * @return \Generator
 */
function iterable_after(iterable $iterable, callable $matcher, bool $including = false): \Generator
{
    $found = false;

    foreach ($iterable as $key => $value) {
        $matched = $found || (bool)call_user_func($matcher, $value, $key);

        if ($found || ($matched && $including)) {
            yield $key => $value;
        }

        $found = $matched;
    }
}
