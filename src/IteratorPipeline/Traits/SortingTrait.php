<?php declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved as i;

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
     * @return static
     */
    abstract public function then(callable $callback, ...$args);


    /**
     * Sort all elements of an iterator.
     *
     * @param callable|int $compare       SORT_* flags as binary set or callback comparator function
     * @param bool         $preserveKeys
     * @return static
     */
    public function sort($compare, bool $preserveKeys = true)
    {
        return $this->then(i\iterable_sort, $compare, $preserveKeys);
    }

    /**
     * Sort all elements of an iterator based on the key.
     *
     * @param callable|int $compare   SORT_* flags as binary set or callback comparator function
     * @return static
     */
    public function sortKeys($compare)
    {
        return $this->then(i\iterable_sort_keys, $compare);
    }

    /**
     * Reverse order of elements of an iterable.
     *
     * @return static
     */
    public function reverse()
    {
        return $this->then(i\iterable_reverse);
    }
}
