<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Aggregator that accumulates the input elements into a new array
 */
abstract class AbstractAggregator implements AggregatorInterface
{
    /**
     * @var \Traversable
     */
    protected $iterator;


    /**
     * Aggregator constructor.
     *
     * @param \Traversable $iterator
     */
    public function __construct(\Traversable $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * Get the iterator.
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return $this->iterator;
    }
}
