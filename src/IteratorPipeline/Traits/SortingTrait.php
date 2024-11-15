<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;

/**
 * Methods that change the order of the elements.
 */
trait SortingTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     */
    abstract public function then(callable $callback, mixed ...$args): static;


    /**
     * Sort all elements of an iterator.
     *
     * @param callable|int $compare       SORT_* flags as binary set or callback comparator function
     * @param bool         $preserveKeys
     * @return static&Pipeline
     */
    public function sort(callable|int $compare, bool $preserveKeys = true): static
    {
        return $this->then("Improved\iterable_sort", $compare, $preserveKeys);
    }

    /**
     * Sort all elements of an iterator based on the key.
     *
     * @param callable|int $compare   SORT_* flags as binary set or callback comparator function
     * @return static&Pipeline
     */
    public function sortKeys(callable|int $compare): static
    {
        return $this->then("Improved\iterable_sort_keys", $compare);
    }

    /**
     * Reverse order of elements of an iterable.
     */
    public function reverse(): static
    {
        return $this->then("Improved\iterable_reverse");
    }
}
