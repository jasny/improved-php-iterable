<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

/**
 * Methods that change the order of the elements.
 */
trait SortingTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed    ...$args
     * @return $this
     */
    abstract public function then(callable $callback, ...$args);


    /**
     * Sort all elements of an iterator.
     *
     * @param callable|int $compare       SORT_* flags as binary set or callback comparator function
     * @param bool         $preserveKeys
     * @return $this
     */
    public function sort($compare, bool $preserveKeys = true)
    {
        return $this->then('Jasny\iterable_sort', $compare, $preserveKeys);
    }

    /**
     * Sort all elements of an iterator based on the key.
     *
     * @param callable|int $compare   SORT_* flags as binary set or callback comparator function
     * @return $this
     */
    public function sortKeys($compare)
    {
        return $this->then('Jasny\iterable_sort_keys', $compare);
    }

    /**
     * Reverse order of elements of an iterable.
     *
     * @return $this
     */
    public function reverse()
    {
        return $this->then('Jasny\iterable_reverse');
    }
}
