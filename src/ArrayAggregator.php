<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Aggregator that accumulates the input elements into a new array
 */
class ArrayAggregator extends AbstractAggregator
{
    /**
     * Invoke the aggregator.
     *
     * @return mixed
     */
    public function __invoke()
    {
        switch (true) {
            case method_exists($this->iterator, 'toArray'):
                return $this->iterator->toArray();
            case method_exists($this->iterator, 'getArrayCopy');
                return $this->iterator->getArrayCopy();
            default:
                return iterator_to_array($this->iterator, true);
        }
    }
}
