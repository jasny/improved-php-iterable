<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Trait to create an array iterator.
 */
trait ArrayIteratorAggregateTrait
{
    /**
     * @var \Traversable
     */
    protected $iterator;

    /**
     * Convert the inner iterator to an ArrayIterator.
     *
     * @return \ArrayIterator
     */
    protected function createArrayIterator(): \ArrayIterator
    {
        switch (true) {
            case $this->iterator instanceof \ArrayIterator:
                return clone $this->iterator;
            case method_exists($this->iterator, 'toArray'):
                return new \ArrayIterator($this->iterator->toArray());
            case method_exists($this->iterator, 'getArrayCopy'):
                return new \ArrayIterator($this->iterator->getArrayCopy());
            default:
                return new \ArrayIterator(iterator_to_array($this->iterator, true));
        }
    }
}
