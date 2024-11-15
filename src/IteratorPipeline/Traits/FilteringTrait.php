<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;

/**
 * Filtering methods for iterator pipeline.
 */
trait FilteringTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed ...$args
     * @return static&Pipeline
     */
    abstract public function then(callable $callback, mixed ...$args): static;


    /**
     * Eliminate elements based on a criteria.
     *
     * @param callable $matcher
     * @return static&Pipeline
     */
    public function filter(callable $matcher): static
    {
        return $this->then("Improved\iterable_filter", $matcher);
    }

    /**
     * Filter out `null` values from iterable.
     *
     * @return static&Pipeline
     */
    public function cleanup(): static
    {
        return $this->then("Improved\iterable_cleanup");
    }

    /**
     * Filter on unique elements.
     *
     * @param callable|null $grouper  If provided, filtering will be based on return value.
     * @return static&Pipeline
     */
    public function unique(?callable $grouper = null): static
    {
        return $this->then("Improved\iterable_unique", $grouper);
    }

    /**
     * Filter our duplicate keys.
     * Unlike associative arrays, the keys of iterators don't have to be unique.
     *
     * @return static&Pipeline
     */
    public function uniqueKeys(): static
    {
        return $this->then("Improved\iterable_unique", function ($value, $key) {
            return $key;
        });
    }

    /**
     * Get only the first elements of an iterator.
     *
     * @param int $size
     * @return static&Pipeline
     */
    public function limit(int $size): static
    {
        return $this->then("Improved\iterable_slice", 0, $size);
    }

    /**
     * Get a limited subset of the elements using an offset.
     *
     * @param int $offset
     * @param int|null $size
     * @return static&Pipeline
     */
    public function slice(int $offset, ?int $size = null): static
    {
        return $this->then("Improved\iterable_slice", $offset, $size);
    }

    /**
     * Get elements until a match is found.
     *
     * @param callable $matcher
     * @param bool     $include
     * @return static&Pipeline
     */
    public function before(callable $matcher, bool $include = false): static
    {
        return $this->then("Improved\iterable_before", $matcher, $include);
    }

    /**
     * Get elements after a match is found.
     *
     * @param callable $matcher
     * @param bool     $include
     * @return static&Pipeline
     */
    public function after(callable $matcher, bool $include = false): static
    {
        return $this->then("Improved\iterable_after", $matcher, $include);
    }
}
