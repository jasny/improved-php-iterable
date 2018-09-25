<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline;

use Jasny\IteratorPipeline\Aggregation\AggregatorInterface;
use Jasny\IteratorPipeline\Aggregation\ArrayAggregator;

class Pipeline implements \IteratorAggregate
{
    /**
     * @var iterable
     */
    protected $iterable;



    /**
     * Get iterable as array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        switch (true) {
            case method_exists($this->iterable, 'toArray'):
                return $this->iterable->toArray();
            case method_exists($this->iterable, 'getArrayCopy'):
                return $this->iterable->getArrayCopy();
            default:
                return iterable_to_array($this->iterable, true);
        }
    }

    /**
     * Get iterator.
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        if (is_array($this->iterable)) {
            return new \ArrayIterator($this->iterable);
        }

        $iterator = $this->iterable instanceof \IteratorAggregate
            ? $this->iterable->getIterator()
            : $this->iterable;

        if (!$iterator instanceof \Iterator) {
            $iterator = new \IteratorIterator($iterator);
        }

        return $iterator;
    }
}
