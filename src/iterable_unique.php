<?php

declare(strict_types=1);

namespace Jasny;

/**
 * Filter to get only unique elements.
 *
 * @param iterable      $iterable
 * @param callable|null $serialize  Callable function to serialize the value
 * @return \Generator
 */
function iterable_unique(iterable $iterable, callable $serialize = null): \Generator
{
    $fastMap = [];
    $slowMap = null;

    foreach ($iterable as $key => $value) {
        $entry = isset($serialize) ? call_user_func($serialize, $value, $key) : $value;

        if (!is_string($entry) && isset($fastMap)) {
            $slowMap = array_flip($fastMap);
            $fastMap = null;
        }

        if (isset($fastMap[$entry]) || in_array($entry, $slowMap, true)) {
            continue;
        }

        if (isset($fastMap)) {
            $fastMap[$entry] = count($fastMap);
        } else {
            $slowMap[] = $entry;
        }

        yield $key => $value;
    }
}
