<?php

declare(strict_types=1);

namespace Ipl\IteratorPipeline\Traits;

use Ipl as i;

/**
 * Filtering methods for iterator pipeline.
 */
trait FilteringTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed    ...$args
     * @return static
     */
    abstract public function then(callable $callback, ...$args);


    /**
     * Eliminate elements based on a criteria.
     *
     * @param callable $matcher
     * @return static
     */
    public function filter(callable $matcher)
    {
        return $this->then(i\iterable_filter, $matcher);
    }

    /**
     * Filter out `null` values from iteratable.
     *
     * @return static
     */
    public function cleanup()
    {
        return $this->then(i\iterable_cleanup);
    }

    /**
     * Filter on unique elements.
     *
     * @param callable|null $grouper  If provided, filtering will be based on return value.
     * @return static
     */
    public function unique(?callable $grouper = null)
    {
        return $this->then(i\iterable_unique, $grouper);
    }

    /**
     * Filter our duplicate keys.
     * Unlike associative arrays, the keys of iterators don't have to be unique.
     *
     * @return static
     */
    public function uniqueKeys()
    {
        return $this->then(i\iterable_unique, function ($value, $key) {
            return $key;
        });
    }

    /**
     * Get only the first elements of an iterator.
     *
     * @param int $size
     * @return static
     */
    public function limit(int $size)
    {
        return $this->then(i\iterable_slice, 0, $size);
    }

    /**
     * Get a limited subset of the elements using an offset.
     *
     * @param int      $offset
     * @param int|null $size    size limit
     * @return static
     */
    public function slice(int $offset, ?int $size = null)
    {
        return $this->then(i\iterable_slice, $offset, $size);
    }


    /**
     * Validate that a value has a specific type.
     * @see https://github.com/jasny/php-functions#expect_type
     *
     * @param string|string[] $type
     * @param string|null     $message
     * @return static
     * @throws \UnexpectedValueException
     */
    public function expectType($type, string $message = null)
    {
        return $this->then(i\iterable_expect_type, $type, \UnexpectedValueException::class, $message);
    }
}
