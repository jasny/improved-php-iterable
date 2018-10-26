<?php declare(strict_types=1);

namespace Improved;

/**
 * Group elements of an array or iterator.
 *
 * @param iterable $iterable
 * @param callable $grouping
 * @return \Generator
 */
function iterable_group(iterable $iterable, callable $grouping): \Generator
{
    $groups = [];
    $values = [];

    foreach ($iterable as $key => $value) {
        $group = call_user_func($grouping, $value, $key);

        $index = array_search($group, $groups, true);

        if ($index === false) {
            $index = array_push($groups, $group) - 1;
        }

        $values[$index][] = $value;
    }

    unset($iterable);

    foreach ($groups as $index => $group) {
        yield $group => $values[$index];
    }
}
