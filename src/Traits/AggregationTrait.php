<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

use function Jasny\iterable_count;
use function Jasny\iterable_reduce;
use function Jasny\iterable_sum;
use function Jasny\iterable_average;
use function Jasny\iterable_concat;

/**
 * Pipeline aggregation methods.
 */
trait AggregationTrait
{
    /**
     * @var iterable
     */
    protected $iterable;

    /**
     * Count elements of an iterable.
     *
     * @return int
     */
    public function count(): int
    {
        return iterable_count($this->iterable);
    }

    /**
     * Reduce all elements to a single value using a callback.
     *
     * @param callable  $callback
     * @param mixed     $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return iterable_reduce($this->iterable, $callback, $initial);
    }

    /**
     * Calculate the sum of all numbers.
     * If no elements are present, the result is 0.
     *
     * @return int|float
     */
    public function sum()
    {
        return iterable_sum($this->iterable);
    }

    /**
     * Return the arithmetic mean.
     * If no elements are present, the result is NAN.
     *
     * @return float
     */
    public function average(): float
    {
        return iterable_average($this->iterable);
    }

    /**
     * Concatenate all elements into a single string.
     *
     * @param string   $glue
     * @return string
     */
    public function concat(string $glue = ''): string
    {
        return iterable_concat($this->iterable, $glue);
    }
}