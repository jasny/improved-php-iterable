<?php

/**
 * Apply a callback to each element.
 *
 * @param iterable $iterable
 * @param callable $callback
 * @return \Generator
 */
function iterable_apply($iterable, $callback)
{
    return jasny\iterable_apply($iterable, $callback);
}

/**
 * Return the arithmetic mean.
 * If no elements are present, the result is NAN.
 *
 * @param iterable $iterable
 * @return float
 */
function iterable_average($iterable)
{
    return jasny\iterable_average($iterable);
}

/**
 * Filter out elements with null value and/or null key from iterator.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_cleanup($iterable)
{
    return jasny\iterable_cleanup($iterable);
}

/**
 * Concatenate all elements into a single string.
 *
 * @param iterable $iterable
 * @param string   $glue
 * @return string
 */
function iterable_concat($iterable, $glue = '')
{
    return jasny\iterable_concat($iterable, $glue);
}

/**
 * Count element of an iterable.
 *
 * @return int
 */
function iterable_count($iterable)
{
    return jasny\iterable_count($iterable);
}

/**
 * Check the type of iterator elements.
 *
 * @param iterable        $iterable
 * @param string|string[] $type
 * @param string          $throwable  Class name
 * @param string          $message
 * @return \Generator
 */
function iterable_expect_type($iterable, $type, $throwable = 'UnexpectedValueException', $message = NULL)
{
    return jasny\iterable_expect_type($iterable, $type, $throwable, $message);
}

/**
 * Filter elements using callback
 *
 * @param iterable $iterable
 * @param callable $matcher
 * @return \Generator
 */
function iterable_filter($iterable, $matcher)
{
    return jasny\iterable_filter($iterable, $matcher);
}

/**
 * Get the first element that matches a condition.
 * Returns null if no element is found.
 *
 * @param iterable $iterable
 * @param callable $matcher
 * @return mixed
 */
function iterable_find($iterable, $matcher)
{
    return jasny\iterable_find($iterable, $matcher);
}

/**
 * Get the first element of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $required  Throw RangeException instead of returning null for empty iterable
 * @return mixed
 */
function iterable_first($iterable, $required = false)
{
    return jasny\iterable_first($iterable, $required);
}

/**
 * Walk through all sub-iterables (array, Iterator or IteratorAggregate) and concatenate them.
 *
 * @param iterable $iterable
 * @param bool     $preserveKeys
 * @return \Generator
 */
function iterable_flatten($iterable, $preserveKeys = false)
{
    return jasny\iterable_flatten($iterable, $preserveKeys);
}

/**
 * Use values as keys and visa versa.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_flip($iterable)
{
    return jasny\iterable_flip($iterable);
}

/**
 * Group elements of an array or iterator.
 *
 * @param iterable $iterable
 * @param callable $grouping
 * @return \Generator
 */
function iterable_group($iterable, $grouping)
{
    return jasny\iterable_group($iterable, $grouping);
}

/**
 * Use the keys as values.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_keys($iterable)
{
    return jasny\iterable_keys($iterable);
}

/**
 * Get the last element of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $required  Throw RangeException instead of returning null for empty iterable
 * @return mixed
 */
function iterable_last($iterable, $required = false)
{
    return jasny\iterable_last($iterable, $required);
}

/**
 * Replace the keys of an iterable using a callback.
 *
 * @param iterable $iterable
 * @param callable $callback
 * @return \Generator
 */
function iterable_map_keys($iterable, $callback)
{
    return jasny\iterable_map_keys($iterable, $callback);
}

/**
 * Applies the callback to the elements of the iterator, replacing the value.
 *
 * @param iterable $iterable
 * @param callable $callback
 * @return \Generator
 */
function iterable_map($iterable, $callback)
{
    return jasny\iterable_map($iterable, $callback);
}

/**
 * Get the maximal element according to a given comparator.
 *
 * @param iterable      $iterable
 * @param callable|null $compare
 * @return mixed
 */
function iterable_max($iterable, $compare = NULL)
{
    return jasny\iterable_max($iterable, $compare);
}

/**
 * Get the minimal element according to a given comparator.
 *
 * @param iterable      $iterable
 * @param callable|null $compare
 * @return mixed
 */
function iterable_min($iterable, $compare = NULL)
{
    return jasny\iterable_min($iterable, $compare);
}

/**
 * Project each element of an iterator to an associated (or numeric) array.
 *
 * @param iterable $iterable
 * @param array $mapping
 * @return \Generator
 */
function iterable_project($iterable, array $mapping)
{
    return jasny\iterable_project($iterable, $mapping);
}

/**
 * Reduce all elements to a single value using a callback.
 *
 * @param iterable  $iterable
 * @param callable  $callback
 * @param mixed     $initial
 * @return mixed
 */
function iterable_reduce($iterable, $callback, $initial = NULL)
{
    return jasny\iterable_reduce($iterable, $callback, $initial);
}

/**
 * Reverse order of elements of an iterable.
 *
 * @param iterable $iterable
 * @param bool     $preserveKeys
 * @return \Generator
 */
function iterable_reverse($iterable, $preserveKeys = false)
{
    return jasny\iterable_reverse($iterable, $preserveKeys);
}

/**
 * Get both keys and values of iterable in separate arrays.
 *
 * @param iterable $iterable
 * @return array[]  ['keys' => array, 'values' => array]
 */
function iterable_separate($iterable)
{
    return jasny\iterable_separate($iterable);
}

/**
 * Get a limited subset of the elements using an offset and (optionally) a limit.
 *
 * @param iterable $iterable
 * @param int      $offset
 * @param int|null $limit
 * @return \Generator
 */
function iterable_slice($iterable, $offset, $limit = NULL)
{
    return jasny\iterable_slice($iterable, $offset, $limit);
}

/**
 * Sort all elements of an iterator based on the key.
 *
 * @param iterable     $iterable
 * @param callable|int $compare   SORT_* flag or callback comparator function
 * @return \Generator
 */
function iterable_sort_keys($iterable, $compare)
{
    return jasny\iterable_sort_keys($iterable, $compare);
}

/**
 * Sort all elements of an iterator.
 *
 * @param iterable     $iterable
 * @param callable|int $compare       SORT_* flag or callback comparator function
 * @param bool         $preserveKeys
 * @return \Generator
 */
function iterable_sort($iterable, $compare, $preserveKeys = false)
{
    return jasny\iterable_sort($iterable, $compare, $preserveKeys);
}

/**
 * Calculate the sum of all numbers.
 * If no elements are present, the result is 0.
 *
 * @param iterable $iterable
 * @return int|float
 */
function iterable_sum($iterable)
{
    return jasny\iterable_sum($iterable);
}

/**
 * Convert any iterable to an array.
 *
 * @param array|\Traversable $iterable
 * @param bool|null          $preserveKeys  NULL means don't care
 * @return array
 */
function iterable_to_array($iterable, $preserveKeys = NULL)
{
    return jasny\iterable_to_array($iterable, $preserveKeys);
}

/**
 * Convert any iterable to an Iterator object.
 *
 * @param array|\Traversable $iterable
 * @return \Iterator
 */
function iterable_to_iterator($iterable)
{
    return jasny\iterable_to_iterator($iterable);
}

/**
 * Convert any iterable to a Traversable object (Iterator or IteratorAggregate).
 *
 * @param array|\Traversable $iterable
 * @return \Traversable
 */
function iterable_to_traversable($iterable)
{
    return jasny\iterable_to_traversable($iterable);
}

/**
 * Filter to get only unique elements.
 *
 * @param iterable      $iterable
 * @param callable|null $serialize  Callable function to serialize the value
 * @return \Generator
 */
function iterable_unique($iterable, $serialize = NULL)
{
    return jasny\iterable_unique($iterable, $serialize);
}

/**
 * Use the values, drop the keys.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_values($iterable)
{
    return jasny\iterable_values($iterable);
}
