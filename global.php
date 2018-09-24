<?php

/**
 * Join an array, using the 'and' parameter as glue the last two items.
 *
 * @param string $glue
 * @param string $and
 * @param array  $array
 * @return string
 */
function array_join_pretty($glue, $and, array $array)
{
    return jasny\array_join_pretty($glue, $and, $array);
}

/**
 * Flatten a nested associative array, concatenating the keys.
 *
 * @param array  $array
 * @param string $glue
 * @return array
 */
function array_flatten(array $array, $glue = '.')
{
    return jasny\array_flatten($array, $glue);
}

/**
 * Find a key of an array using a callback function.
 * @see array_filter()
 *
 * Returns the key or FALSE if no element was found.
 *
 * @param array    $array
 * @param callable $callback
 * @param int      $flag      Flag determining what arguments are sent to callback
 * @return string|int|false
 */
function array_find_key(array $array, $callback, $flag = 0)
{
    return jasny\array_find_key($array, $callback, $flag);
}

/**
 * Find an element of an array using a callback function.
 * @see array_filter()
 *
 * Returns the value or FALSE if no element was found.
 *
 * @param array    $array
 * @param callable $callback
 * @param int      $flag      Flag determining what arguments are sent to callback
 * @return mixed|false
 */
function array_find(array $array, $callback, $flag = 0)
{
    return jasny\array_find($array, $callback, $flag);
}

/**
 * Check if an array contains any value in a set with index check.
 *
 * @param array $array
 * @param array $subset
 * @param bool  $strict  Strict type checking
 * @return bool
 */
function array_contains_any_assoc(array $array, array $subset, $strict = false)
{
    return jasny\array_contains_any_assoc($array, $subset, $strict);
}

/**
 * Check if an array contains any value in a set.
 **
 * @param array $array
 * @param array $subset
 * @param bool  $strict  Strict type checking
 * @return bool
 */
function array_contains_any(array $array, array $subset, $strict = false)
{
    return jasny\array_contains_any($array, $subset, $strict);
}

/**
 * Check if an array contains all values in a set with index check.
 *
 * @param array $array
 * @param array $subset
 * @param bool  $strict  Strict type checking
 * @return bool
 */
function array_contains_all_assoc(array $array, array $subset, $strict = false)
{
    return jasny\array_contains_all_assoc($array, $subset, $strict);
}

/**
 * Check if an array contains all values in a set.
 *
 * @param array $array
 * @param array $subset
 * @param bool  $strict  Strict type checking
 * @return bool
 */
function array_contains_all(array $array, array $subset, $strict = false)
{
    return jasny\array_contains_all($array, $subset, $strict);
}

/**
 * Return an array without the specified keys.
 *
 * @param array          $array
 * @param string[]|int[] $keys
 * @return array
 */
function array_without(array $array, array $keys)
{
    return jasny\array_without($array, $keys);
}

/**
 * Return an array with only the specified keys.
 *
 * @param array          $array
 * @param string[]|int[] $keys
 * @return array
 */
function array_only(array $array, array $keys)
{
    return jasny\array_only($array, $keys);
}

/**
 * Turn StudlyCase, camelCase, snake_case or kabab-case into a sentence.
 *
 * @param string $string
 * @return string
 */
function uncase($string)
{
    return jasny\uncase($string);
}

/**
 * Turn a sentence, camelCase, StudlyCase or snake_case into kabab-case
 *
 * @param string $string
 * @return string
 */
function kababcase($string)
{
    return jasny\kababcase($string);
}

/**
 * Turn a sentence, camelCase, StudlyCase or kabab-case into snake_case
 *
 * @param string $string
 * @return string
 */
function snakecase($string)
{
    return jasny\snakecase($string);
}

/**
 * Turn a sentence, camelCase, snake_case or kabab-case into StudlyCase
 *
 * @param string $string
 * @return string
 */
function studlycase($string)
{
    return jasny\studlycase($string);
}

/**
 * Turn a sentence, camelCase, snake_case or kabab-case into camelCase
 *
 * @param string $string
 * @return string
 */
function camelcase($string)
{
    return jasny\camelcase($string);
}

/**
 * Match path against an extended wildcard pattern.
 *
 * @param string $pattern
 * @param string $path
 * @return bool
 */
function fnmatch_extended($pattern, $path)
{
    return jasny\fnmatch_extended($pattern, $path);
}

/**
 * Check if the file contains the specified string
 *
 * @param string $filename
 * @param string $str
 * @return bool
 */
function file_contains($filename, $str)
{
    return jasny\file_contains($filename, $str);
}

/**
 * Call a callback with named parameters as associative array.
 *
 * @param callable $callback
 * @param array    $params_arr
 * @return mixed
 * @throws \BadFunctionCallException
 * @throws \ReflectionException
 */
function call_user_func_assoc($callback, array $params_arr)
{
    return jasny\call_user_func_assoc($callback, $params_arr);
}

/**
 * Set the public properties of an object
 *
 * @param object $object
 * @param array  $data
 * @param bool   $dynamic  Set properties not defined in the class
 * @return void
 */
function object_set_properties($object, array $data, $dynamic = false)
{
    return jasny\object_set_properties($object, $data, $dynamic);
}

/**
 * Get the public properties of an object.
 * This is an alias of `get_object_vars`, except if will always return public properties only.
 *
 * @param object $object
 * @param bool   $dynamic  Get properties not defined in the class
 * @return array
 */
function object_get_properties($object, $dynamic = false)
{
    return jasny\object_get_properties($object, $dynamic);
}

/**
 * Converts inet_pton output to string with bits.
 *
 * @param string $inet
 * @return string
 */
function inet_to_bits($inet)
{
    return jasny\inet_to_bits($inet);
}

/**
 * Check if IPv6 address is in CIDR block
 *
 * @param string $ip
 * @param string $cidr
 * @return bool
 */
function ipv6_in_cidr($ip, $cidr)
{
    return jasny\ipv6_in_cidr($ip, $cidr);
}

/**
 * Check if IPv4 address is in CIDR block
 *
 * @param string $ip
 * @param string $cidr
 * @return bool
 */
function ipv4_in_cidr($ip, $cidr)
{
    return jasny\ipv4_in_cidr($ip, $cidr);
}

/**
 * Check if IP address is in CIDR block
 *
 * @param string $ip     An IPv4 or IPv6
 * @param string $cidr   An IPv4 CIDR block or IPv6 CIDR block
 * @return bool
 */
function ip_in_cidr($ip, $cidr)
{
    return jasny\ip_in_cidr($ip, $cidr);
}

/**
 * Convert an IPv4 address or CIDR into an IP6 address or CIDR.
 *
 * @param string $ip
 * @return string
 * @throws \InvalidArgumentException if ip isn't valid
 */
function ipv4_to_ipv6($ip)
{
    return jasny\ipv4_to_ipv6($ip);
}

/**
 * Generate a URL friendly slug from the given string.
 *
 * @param string $string
 * @param string $glue
 * @return string
 */
function str_slug($string, $glue = '-')
{
    return jasny\str_slug($string, $glue);
}

/**
 * Replace characters with accents with normal characters.
 *
 * @param string $string
 * @return string
 */
function str_remove_accents($string)
{
    return jasny\str_remove_accents($string);
}

/**
 * Get the string after the first occurence of the substring.
 * If the substring is not found, an empty string is returned.
 *
 * @param string $string
 * @param string $substr
 * @return string
 */
function str_after($string, $substr)
{
    return jasny\str_after($string, $substr);
}

/**
 * Get the string before the first occurence of the substring.
 * If the substring is not found, the whole string is returned.
 *
 * @param string $string
 * @param string $substr
 * @return string
 */
function str_before($string, $substr)
{
    return jasny\str_before($string, $substr);
}

/**
 * Check if a string contains a substring
 *
 * @param string $string
 * @param string $substr
 * @return bool
 */
function str_contains($string, $substr)
{
    return jasny\str_contains($string, $substr);
}

/**
 * Check if a string ends with a substring
 *
 * @param string $string
 * @param string $substr
 * @return bool
 */
function str_ends_with($string, $substr)
{
    return jasny\str_ends_with($string, $substr);
}

/**
 * Check if a string starts with a substring
 *
 * @param string $string
 * @param string $substr
 * @return bool
 */
function str_starts_with($string, $substr)
{
    return jasny\str_starts_with($string, $substr);
}

/**
 * Check that an argument has a specific type, otherwise throw an exception.
 *
 * @param mixed           $var
 * @param string|string[] $type
 * @param string          $throwable  Class name
 * @param string          $message
 * @return void
 */
function expect_type($var, $type, $throwable = 'TypeError', $message = NULL)
{
    return jasny\expect_type($var, $type, $throwable, $message);
}

/**
 * Get the type of a variable in a descriptive way.
 *
 * @param mixed $var
 * @return string
 */
function get_type_description($var)
{
    return jasny\get_type_description($var);
}

/**
 * Turn stdClass object into associated array recursively.
 *
 * @param \stdClass|mixed $var
 * @return array|mixed
 */
function arrayify($var)
{
    return jasny\arrayify($var);
}

/**
 * Turn associated array into stdClass object recursively.
 *
 * @param array|mixed $var
 * @return \stdClass|mixed
 */
function objectify($var)
{
    return jasny\objectify($var);
}

/**
 * Check if variable is a numeric array.
 *
 * @param array|mixed $var
 * @return bool
 */
function is_numeric_array($var)
{
    return jasny\is_numeric_array($var);
}

/**
 * Check if variable is an associative array.
 *
 * @param array|mixed $var
 * @return bool
 */
function is_associative_array($var)
{
    return jasny\is_associative_array($var);
}

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
 * @return float
 */
function iterable_average($iterable)
{
    return jasny\iterable_average($iterable);
}

/**
 * Filter out null elements from iterator.
 *
 * @param iterable $iterable
 * @return \Generator
 */
function iterable_cleanup($iterable)
{
    return jasny\iterable_cleanup($iterable);
}

/**
 * Invoke the aggregator.
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
 * Count iterable.
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
 * @throws \Throwable if type doesn't match
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
 * Returns null if the iterable empty.
 *
 * @param iterable $iterable
 * @return mixed
 */
function iterable_first($iterable)
{
    return jasny\iterable_first($iterable);
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
 * Returns null if the iterable empty.
 *
 * @param iterable $iterable
 * @return mixed
 */
function iterable_last($iterable)
{
    return jasny\iterable_last($iterable);
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
 * Scalar, null and resource elements are untouched.
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
 * Sort all elements of an iterator based on the key.
 *
 * @param iterable     $iterable
 * @param callable|int $compare   SORT_* flags as binary set or callback comparator function
 * @return \Generator
 */
function iterable_sort_keys($iterable, $compare = 0)
{
    return jasny\iterable_sort_keys($iterable, $compare);
}

/**
 * Sort all elements of an iterator.
 *
 * @param iterable     $iterable
 * @param callable|int $compare       SORT_* flags as binary set or callback comparator function
 * @param bool         $preserveKeys
 * @return iterable
 */
function iterable_sort($iterable, $compare = 0, $preserveKeys = false)
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
 * @return array
 */
function iterable_to_array($iterable)
{
    return jasny\iterable_to_array($iterable);
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
