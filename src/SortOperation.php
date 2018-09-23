<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Sort all elements of an iterator.
 */
class SortOperation implements \IteratorAggregate
{
    use ArrayIteratorAggregateTrait;

    /**
     * @var callable
     */
    protected $compare;


    /**
     * AbstractIterator constructor.
     *
     * @param \Traversable $iterator
     * @param callable     $compare
     */
    public function __construct(\Traversable $iterator, callable $compare = null)
    {
        $this->compare = $compare;
        $this->iterator = $iterator;
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
            $sortedIterator->uasort($this->compare);
        } else {
            $sortedIterator->asort();
        }

        return $sortedIterator;
    }
}
