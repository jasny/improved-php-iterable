<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved as i;

/**
 * Pipeline methods for finding an element in an iterable.
 */
trait FindingTrait
{
    /**
     * @var iterable<mixed>
     */
    protected iterable $iterable;


    /**
     * Get the first value of an iterable.
     *
     * @param bool $required  Throw RangeException instead of returning null for empty iterable
     * @return mixed
     */
    public function first(bool $required = false): mixed
    {
        return i\iterable_first($this->iterable, $required);
    }

    /**
     * Get the last value of an iterable.
     *
     * @param bool $required  Throw RangeException instead of returning null for empty iterable
     * @return mixed
     */
    public function last(bool $required = false): mixed
    {
        return i\iterable_last($this->iterable, $required);
    }


    /**
     * Get the first value that matches a condition.
     * Returns null if no element is found.
     */
    public function find(callable $matcher): mixed
    {
        return i\iterable_find($this->iterable, $matcher);
    }

    /**
     * Get the first value that matches a condition and return the key.
     * Returns null if no element is found.
     */
    public function findKey(callable $matcher): mixed
    {
        return i\iterable_find_key($this->iterable, $matcher);
    }


    /**
     * Check if any element matches the condition.
     */
    public function hasAny(callable $matcher): bool
    {
        return i\iterable_has_any($this->iterable, $matcher);
    }

    /**
     * Check if all elements match the condition.
     */
    public function hasAll(callable $matcher): bool
    {
        return i\iterable_has_all($this->iterable, $matcher);
    }

    /**
     * Check that no elements match the condition.
     */
    public function hasNone(callable $matcher): bool
    {
        return i\iterable_has_none($this->iterable, $matcher);
    }


    /**
     * Get the minimal value according to a given comparator.
     * Returns null for an empty iterable.
     *
     * @param callable|null $compare
     * @return mixed
     */
    public function min(?callable $compare = null): mixed
    {
        return i\iterable_min($this->iterable, $compare);
    }

    /**
     * Get the maximal value according to a given comparator.
     * Returns null for an empty iterable.
     *
     * @param callable|null $compare
     * @return mixed
     */
    public function max(?callable $compare = null): mixed
    {
        return i\iterable_max($this->iterable, $compare);
    }
}
