<?php declare(strict_types=1);

namespace Improved;

/**
 * Filter to get only unique elements.
 *
 * @param iterable      $iterable
 * @param callable|null $serialize  Callable function to serialize the value
 * @return \Generator
 */
function iterable_unique(iterable $iterable, callable $serialize = null): \Generator
{
    $fastMap = []; // entries as keys, entry must be a string
    $slowMap = []; // non-string entries

    foreach ($iterable as $key => $value) {
        $entry = isset($serialize) ? call_user_func($serialize, $value, $key) : $value;

        if (is_string($entry) ? isset($fastMap[$entry]) : in_array($entry, $slowMap, true)) {
            continue;
        }

        if (is_string($entry)) {
            $fastMap[$entry] = true;
        } else {
            $slowMap[] = $entry;
        }

        yield $key => $value;
    }
}
