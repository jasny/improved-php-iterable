<?php declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved as i;
use Improved\Iterator\CombineIterator;

/**
 * Mapping and projection methods for iterator pipeline
 */
trait MappingTrait
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
     * Map each element to a value using a callback function.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        return $this->then(i\iterable_map, $callback);
    }

    /**
     * Map the key of each element to a new key using a callback function.
     *
     * @param callable $callback
     * @return static
     */
    public function mapKeys(callable $callback)
    {
        return $this->then(i\iterable_map_keys, $callback);
    }

    /**
     * Apply a callback to each element of an iterator.
     * Any value returned by the callback is ignored.
     *
     * @param callable $callback
     * @return static
     */
    public function apply(callable $callback)
    {
        return $this->then(i\iterable_apply, $callback);
    }

    /**
     * Divide iterable into chunks of specified size.
     *
     * @param int $size
     * @return static
     */
    public function chunk(int $size)
    {
        return $this->then(i\iterable_chunk, $size);
    }

    /**
     * Group elements of an iterator, with the group name as key and an array of elements as value.
     *
     * @param callable $grouping
     * @return static
     */
    public function group(callable $grouping)
    {
        return $this->then(i\iterable_group, $grouping);
    }

    /**
     * Walk through all sub-iterables and concatenate them.
     *
     * @param bool $preserveKeys
     * @return static
     */
    public function flatten(bool $preserveKeys = false)
    {
        return $this->then(i\iterable_flatten, $preserveKeys);
    }

    /**
     * Deconstruct an iterable property/item for each element. The result is one element for each item in the iterable
     * property.
     *
     * @param string      $column
     * @param string|null $mapKey        The name of a new property to hold the array index of the element
     * @param bool        $preserveKeys  Preserve the keys of the iterable (will result in duplicate keys)
     * @return static
     */
    public function unwind(string $column, ?string $mapKey = null, bool $preserveKeys = false)
    {
        return $this->then(i\iterable_unwind, $column, $mapKey, $preserveKeys);
    }

    /**
     * Set all values of the iterable.
     *
     * @param mixed $value
     * @return static
     */
    public function fill($value)
    {
        return $this->then(i\iterable_fill, $value);
    }

    /**
     * Return the values from a single column / property.
     * Create key/value pairs by specifying the key.
     *
     * @param string|int|null $valueColumn  null to keep values
     * @param string|int|null $keyColumn    null to keep keys
     * @return static
     */
    public function column($valueColumn, $keyColumn = null)
    {
        return $this->then(i\iterable_column, $valueColumn, $keyColumn);
    }

    /**
     * Project each element of an iterator to an associated (or numeric) array.
     * Each element should be an array or object.
     *
     * @param array $mapping  [new key => old key, ...]
     * @return static
     */
    public function project(array $mapping)
    {
        return $this->then(i\iterable_project, $mapping);
    }

    /**
     * Reshape each element of an iterator, adding or removing properties or keys.
     *
     * @param array $columns  [key => bool|string|int, ...]
     * @return static
     */
    public function reshape(array $columns)
    {
        return $this->then(i\iterable_reshape, $columns);
    }


    /**
     * Keep the values, drop the keys. The keys become an incremental number.
     *
     * @return static
     */
    public function values()
    {
        return $this->then(i\iterable_values);
    }

    /**
     * Use the keys as values. The keys become an incremental number.
     *
     * @return static
     */
    public function keys()
    {
        return $this->then(i\iterable_keys);
    }

    /**
     * Use another iterator as keys and the current iterator as values.
     *
     * @param iterable $keys
     * @return static
     */
    public function setKeys(iterable $keys)
    {
        $combine = function ($values, $keys) {
            return new CombineIterator($keys, $values);
        };

        return $this->then($combine, $keys);
    }

    /**
     * Use values as keys and visa versa.
     *
     * @return static
     */
    public function flip()
    {
        return $this->then(i\iterable_flip);
    }
}
