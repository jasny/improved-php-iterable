<?php declare(strict_types=1);

namespace Improved;

/**
 * Get elements until a match is found.
 *
 * @param iterable $iterable
 * @param callable $matcher
 * @param bool     $including
 * @return \Generator
 */
function iterable_before(iterable $iterable, callable $matcher, bool $including = false): \Generator
{
    foreach ($iterable as $key => $value) {
        $matched = (bool)call_user_func($matcher, $value, $key);

        if (!$matched || $including) {
            yield $key => $value;
        }

        if ($matched) {
            return;
        }
    }
}
