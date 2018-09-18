<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Aggregate input elements, resulting in an accumulated value.
 */
interface Aggregator
{
    /**
     * Aggregator constructor.
     *
     * @param \Traversable $iterator
     */
    public function __construct(\Traversable $iterator);

    /**
     * Get the iterator.
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable;

    /**
     * Invoke the aggregator.
     *
     * @return mixed
     */
    public function __invoke();
}
