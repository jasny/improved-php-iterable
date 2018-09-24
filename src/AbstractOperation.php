<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Operation;

/**
 * Base class for iterator operations.
 */
abstract class AbstractOperation implements \IteratorAggregate
{
    /**
     * @var iterable
     */
    protected $input;

    /**
     * Class constructor.
     *
     * @param iterable $input
     */
    public function __construct(iterable $input)
    {
        $this->input = $input;
    }

    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Traversable
     */
    abstract protected function apply(): \Traversable;

    /**
     * Get iterator
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return $this->apply();
    }
}
