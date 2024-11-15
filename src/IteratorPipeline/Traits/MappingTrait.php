<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved as i;
use Improved\Iterator\CombineIterator;
use Improved\IteratorPipeline\Pipeline;

/**
 * Mapping and projection methods for iterator pipeline
 */
trait MappingTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     */
    abstract public function then(callable $callback, mixed ...$args): static;


    /**
     * Map each element to a value using a callback function.
     */
    public function map(callable $callback): static
    {
        return $this->then("Improved\iterable_map", $callback);
    }

    /**
     * Map the key of each element to a new key using a callback function.
     */
    public function mapKeys(callable $callback): static
    {
        return $this->then("Improved\iterable_map_keys", $callback);
    }

    /**
     * Apply a callback to each element of an iterator.
     * Any value returned by the callback is ignored.
     */
    public function apply(callable $callback): static
    {
        return $this->then("Improved\iterable_apply", $callback);
    }

    /**
     * Divide iterable into chunks of specified size.
     */
    public function chunk(int $size): static
    {
        return $this->then("Improved\iterable_chunk", $size);
    }

    /**
     * Group elements of an iterator, with the group name as key and an array of elements as value.
     */
    public function group(callable $grouping): static
    {
        return $this->then("Improved\iterable_group", $grouping);
    }

    /**
     * Walk through all sub-iterables and concatenate them.
     */
    public function flatten(bool $preserveKeys = false): static
    {
        return $this->then("Improved\iterable_flatten", $preserveKeys);
    }

    /**
     * Deconstruct an iterable property/item for each element. The result is one element for each item in the iterable
     * property.
     *
     * @param string      $column
     * @param string|null $mapKey        The name of a new property to hold the array index of the element
     * @param bool        $preserveKeys  Preserve the keys of the iterable (will result in duplicate keys)
     * @return static&Pipeline
     */
    public function unwind(string $column, ?string $mapKey = null, bool $preserveKeys = false): static
    {
        return $this->then("Improved\iterable_unwind", $column, $mapKey, $preserveKeys);
    }

    /**
     * Set all values of the iterable.
     */
    public function fill(mixed $value): static
    {
        return $this->then("Improved\iterable_fill", $value);
    }

    /**
     * Return the values from a single column / property.
     * Create key/value pairs by specifying the key.
     *
     * @param string|int|null $valueColumn  null to keep values
     * @param string|int|null $keyColumn    null to keep keys
     * @return static&Pipeline
     */
    public function column(string|int|null $valueColumn, string|int|null $keyColumn = null): static
    {
        return $this->then("Improved\iterable_column", $valueColumn, $keyColumn);
    }

    /**
     * Project each element of an iterator to an associated (or numeric) array.
     * Each element should be an array or object.
     *
     * @param array<mixed, mixed> $mapping  [new key => old key, ...]
     * @return static&Pipeline
     */
    public function project(array $mapping): static
    {
        return $this->then("Improved\iterable_project", $mapping);
    }

    /**
     * Reshape each element of an iterator, renaming or removing properties or keys.
     *
     * @param array<mixed, bool|string|int> $columns  [key => bool|string|int, ...]
     * @return static&Pipeline
     */
    public function reshape(array $columns): static
    {
        return $this->then("Improved\iterable_reshape", $columns);
    }


    /**
     * Keep the values, drop the keys. The keys become an incremental number.
     */
    public function values(): static
    {
        return $this->then("Improved\iterable_values");
    }

    /**
     * Use the keys as values. The keys become an incremental number.
     */
    public function keys(): static
    {
        return $this->then("Improved\iterable_keys");
    }

    /**
     * Use another iterator as keys and the current iterator as values.
     */
    public function setKeys(iterable $keys): static
    {
        $combine = function ($values, $keys) {
            return new CombineIterator($keys, $values);
        };

        return $this->then($combine, $keys);
    }

    /**
     * Use values as keys and visa versa.
     */
    public function flip(): static
    {
        return $this->then("Improved\iterable_flip");
    }
}
