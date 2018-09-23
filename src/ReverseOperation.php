<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Reverse order of elements of an iterator.
 */
class ReverseOperation implements \IteratorAggregate
{
    use ArrayIteratorAggregateTrait;

    /**
     * AbstractIterator constructor.
     *
     * @param \Traversable $iterator
     */
    public function __construct(\Traversable $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * Get the iterator with sorted values
     *
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        $array = $this->createArrayIterator()->getArrayCopy();

        return new \ArrayIterator(array_reverse($array, true));
    }
}
