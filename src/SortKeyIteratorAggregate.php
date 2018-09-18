<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Sort all elements of an iterator based on the key.
 */
class SortKeyIteratorAggregate implements \OuterIterator
{
    /**
     * @var \Iterator|\ArrayIterator
     */
    protected $iterator;

    /**
     * @var callable
     */
    protected $compare;


    /**
     * AbstractIterator constructor.
     *
     * @param \Iterator $iterator
     * @param callable  $compare
     */
    public function __construct(\Iterator $iterator, callable $compare = null)
    {
        $this->compare = $compare;

        if ($iterator instanceof \ArrayIterator) {
            $this->iterator = clone $iterator;
            $this->sort();
        } else {
            $this->iterator = $iterator;
        }
    }

    /**
     * Sort the values of the iterator.
     * Requires traversing through the iterator, turning it into an array.
     *
     * @return void
     */
    protected function sort(): void
    {
        if (!$this->iterator instanceof \ArrayIterator) {
            $elements = iterator_to_array($this->iterator);
            $this->iterator = new \ArrayIterator($elements);
        }

        if (isset($this->compare)) {
            $this->iterator->uksort($this->compare);
        } else {
            $this->iterator->ksort();
        }
    }

    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return $this->getInnerIterator()->current();
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
        $this->getInnerIterator()->next();
    }

    /**
     * Return the key of the current element
     *
     * @return mixed
     */
    public function key()
    {
        return $this->getInnerIterator()->key();
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->getInnerIterator()->valid();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->getInnerIterator()->rewind();
    }


    /**
     * Returns the inner iterator.
     *
     * @return \Iterator
     */
    public function getInnerIterator(): \Iterator
    {
        if (!$this->iterator instanceof \ArrayIterator) {
            $this->sort();
        }

        return $this->iterator;
    }
}
