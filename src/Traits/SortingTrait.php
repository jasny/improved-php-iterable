<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

use function Jasny\iterable_sort;
use function Jasny\iterable_sort_keys;
use function Jasny\iterable_reverse;
use Jasny\IteratorPipeline\Pipeline;

/**
 * Methods that change the order of the elements.
 */
trait SortingTrait
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
    abstract protected function step(iterable $iterable): Pipeline;


    /**
     * Sort all elements of an iterator.
     *
     * @param callable|int $compare       SORT_* flags as binary set or callback comparator function
     * @param bool         $preserveKeys
     * @return $this
     */
    public function sort($compare, bool $preserveKeys = true): Pipeline
    {
        return $this->step(iterable_sort($this->iterable, $compare, $preserveKeys));
    }

    /**
     * Sort all elements of an iterator based on the key.
     *
     * @param callable|int $compare   SORT_* flags as binary set or callback comparator function
     * @return Pipeline
     */
    public function sortKeys($compare): Pipeline
    {
        return $this->step(iterable_sort_keys($this->iterable, $compare));
    }

    /**
     * Reverse order of elements of an iterable.
     *
     * @return $this
     */
    public function reverse(): Pipeline
    {
        return $this->step(iterable_reverse($this->iterable));
    }
}
