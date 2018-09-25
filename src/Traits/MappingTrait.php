<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

use Jasny\IteratorPipeline\Pipeline;
use Jasny\Iterator\CombineIterator;
use function Jasny\iterable_map;
use function Jasny\iterable_map_keys;
use function Jasny\iterable_apply;
use function Jasny\iterable_group;
use function Jasny\iterable_flatten;
use function Jasny\iterable_project;
use function Jasny\iterable_values;
use function Jasny\iterable_keys;
use function Jasny\iterable_flip;
use function Jasny\expect_type;

/**
 * Mapping and projection methods for iterator pipeline
 */
trait MappingTrait
{
    /**
     * @var iterable
     */
    protected $iterable;

    /**
     * Set the next step of the pipeline.
     *
     * @param iterable
     * @return $this
     */
    abstract protected function step(iterable $iterable);


    /**
     * Map each element to a value using a callback function.
     *
     * @param callable $callback
     * @return $this
     */
    public function map(callable $callback): Pipeline
    {
        return $this->step(iterable_map($this->iterable, $callback));
    }

    /**
     * Map the key of each element to a new key using a callback function.
     *
     * @param callable $callback
     * @return $this
     */
    public function mapKeys(callable $callback): Pipeline
    {
        return $this->step(iterable_map_keys($this->iterable, $callback));
    }

    /**
     * Apply a callback to each element of an iterator.
     * Any value returned by the callback is ignored.
     *
     * @param callable $callback
     * @return $this
     */
    public function apply(callable $callback): Pipeline
    {
        return $this->step(iterable_apply($this->iterable, $callback));
    }

    /**
     * Define the next step via a callback that returns a `Generator` or other `Traversable`.
     *
     * @param callable $callback
     * @return $this
     */
    public function then(callable $callback): Pipeline
    {
        $next = $callback($this->iterable);
        expect_type($next, 'iterable', \UnexpectedValueException::class,
            "Expected callback to return an array or Traversable, %s returned");

        return $this->step($next);
    }

    /**
     * Group elements of an iterator, with the group name as key and an array of elements as value.
     *
     * @param callable $grouping
     * @return $this
     */
    public function group(callable $grouping): Pipeline
    {
        return $this->step(iterable_group($this->iterable, $grouping));
    }

    /**
     * Walk through all sub-iterables and concatenate them.
     *
     * @return $this
     */
    public function flatten(): Pipeline
    {
        return $this->step(iterable_flatten($this->iterable));
    }

    /**
     * Project each element of an iterator to an associated (or numeric) array. Each element should be an array or object.
     *
     * @param array $mapping  [new key => old key, ...]
     * @return $this
     */
    public function project(array $mapping): Pipeline
    {
        return $this->step(iterable_project($this->iterable, $mapping));
    }


    /**
     * Keep the values, drop the keys. The keys become an incremental number.
     *
     * @return $this
     */
    public function values(): Pipeline
    {
        return $this->step(iterable_values($this->iterable));
    }

    /**
     * Use the keys as values. The keys become an incremental number.
     *
     * @return $this
     */
    public function keys(): Pipeline
    {
        return $this->step(iterable_keys($this->iterable));
    }

    /**
     * Use another iterator as keys and the current iterator as values.
     *
     * @param iterable $keys
     * @return $this
     */
    public function setKeys(iterable $keys): Pipeline
    {
        return $this->step(new CombineIterator($keys, $this->iterable));
    }

    /**
     * Use values as keys and visa versa.
     *
     * @return Pipeline
     */
    public function flip(): Pipeline
    {
        return $this->step(iterable_flip($this->iterable));
    }
}
