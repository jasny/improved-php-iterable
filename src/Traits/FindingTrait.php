<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

use function Jasny\iterable_first;
use function Jasny\iterable_last;
use function Jasny\iterable_find;
use function Jasny\iterable_min;
use function Jasny\iterable_max;

/**
 * Pipeline methods for finding an element in an iterable.
 */
trait FindingTrait
{
    /**
     * @var iterable
     */
    protected $iterable;


    /**
     * Get the first value of an iterable.
     *
     * @param bool $required  Throw RangeException instead of returning null for empty iterable
     * @return mixed
     */
    public function first(bool $required = false)
    {
        return iterable_first($this->iterable, $required);
    }

    /**
     * Get the last value of an iterable.
     *
     * @param bool $required  Throw RangeException instead of returning null for empty iterable
     * @return mixed
     */
    public function last(bool $required = false)
    {
        return iterable_last($this->iterable, $required);
    }


    /**
     * Get the first value that matches a condition.
     * Returns null if no element is found.
     *
     * @param callable $matcher
     * @return mixed
     */
    public function find(callable $matcher)
    {
        return iterable_find($this->iterable, $matcher);
    }


    /**
     * Get the minimal value according to a given comparator.
     * Returns null for an empty iterable.
     *
     * @param callable|null $compare
     * @return mixed
     */
    public function min(?callable $compare = null)
    {
        return iterable_min($this->iterable, $compare);
    }

    /**
     * Get the maximal value according to a given comparator.
     * Returns null for an empty iterable.
     *
     * @param callable|null $compare
     * @return mixed
     */
    public function max(?callable $compare = null)
    {
        return iterable_max($this->iterable, $compare);
    }
}
