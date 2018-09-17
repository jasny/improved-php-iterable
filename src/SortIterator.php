<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Sort all elements of an iterator.
 */
class SortIterator implements \OuterIterator
{
    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @var \ArrayIterator
     */
    protected $sortedIterator;

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
        $this->iterator = $iterator;
    }


    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return $this->getSortedIterator()->current();
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
        $this->getSortedIterator()->next();
    }

    /**
     * Return the key of the current element
     *
     * @return mixed
     */
    public function key()
    {
        return $this->getSortedIterator()->key();
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->getSortedIterator()->valid();
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->getSortedIterator()->rewind();
    }


    /**
     * Convert the inner iterator to an ArrayIterator.
     *
     * @return \ArrayIterator
     */
    protected function createArrayIterator(): \ArrayIterator
    {
        if ($this->iterator instanceof \ArrayIterator) {
            return clone $this->iterator;
        }

        $array = method_exists($this->iterator, 'toArray')
            ? $this->iterator->toArray()
            : iterator_to_array($this->iterator);

        return new \ArrayIterator($array);
    }

    /**
     * Sort the values of the iterator.
     * Requires traversing through the iterator, turning it into an array.
     *
     * @return void
     */
    protected function initSortedIterator(): void
    {
        $this->sortedIterator = $this->createArrayIterator();

        if (isset($this->compare)) {
            $this->sortedIterator->uasort($this->compare);
        } else {
            $this->sortedIterator->asort();
        }
    }

    /**
     * Get the iterator with sorted values
     *
     * @return \ArrayIterator
     */
    protected function getSortedIterator(): \ArrayIterator
    {
        if (!isset($this->sortedIterator)) {
            $this->initSortedIterator();
        }

        return $this->sortedIterator;
    }


    /**
     * Returns the inner iterator.
     *
     * @return \Iterator
     */
    public function getInnerIterator(): \Iterator
    {
        return $this->iterator;
    }
}
