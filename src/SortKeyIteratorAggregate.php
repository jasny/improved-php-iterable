<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Sort all elements of an iterator based on the key.
 */
class SortKeyIteratorAggregate implements \IteratorAggregate
{
    /**
     * @var \Iterator
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
        $this->iterator = $iterator;
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
     * Get the iterator with sorted values
     *
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        $sortedIterator = $this->createArrayIterator();

        if (isset($this->compare)) {
            $sortedIterator->uksort($this->compare);
        } else {
            $sortedIterator->ksort();
        }

        return $sortedIterator;
    }
}
