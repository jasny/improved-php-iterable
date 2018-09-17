<?php

declare(strict_types=1);

namespace Jasny\Iterator;

use SebastianBergmann\CodeCoverage\Node\Iterator;

/**
 * Take all sub-array elements and concatenate it recursively.
 */
class FlattenIterator implements \OuterIterator
{
    /**
     * @var \Iterator  Top iterator
     */
    protected $iterator;

    /**
     * @var \Iterator|null
     */
    protected $currentIterator;

    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @var bool
     */
    protected $preserveKeys;


    /**
     * Class constructor.
     *
     * @param \Iterator $iterator
     * @param bool      $preserveKeys
     */
    public function __construct(\Iterator $iterator, bool $preserveKeys = false)
    {
        $this->iterator = $iterator;
        $this->preserveKeys = $preserveKeys;

        $this->prepareNext();
    }

    /**
     * Create Iterator for entries.
     *
     * @param mixed $entries
     * @return \Iterator
     */
    protected function createEntriesIterator($entries): \Iterator
    {
        if (is_array($entries)) {
            return new \ArrayIterator($entries);
        }

        if ($entries instanceof \Iterator) {
            return $entries;
        }

        if ($entries instanceof \IteratorAggregate) {
            return $entries->getIterator();
        }

        $type = (is_object($entries) ? get_class($entries) . ' ' : '') . gettype($entries);
        throw new \UnexpectedValueException("Expected an array, Iterator or IteratorAggregate, got $type");
    }

    /**
     * Prepare new currentIterator
     *
     * @return void
     */
    protected function prepareNext(): void
    {
        if (!$this->iterator->valid()) {
            return;
        }

        $entries = $this->iterator->current();
        $this->currentIterator = $this->createEntriesIterator($entries);

        $this->iterator->next();
    }


    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current()
    {
        return isset($this->currentIterator) ? $this->currentIterator->current() : null;
    }

    /**
     * Move forward to next element
     *
     * @return void
     */
    public function next(): void
    {
        if (!isset($this->currentIterator)) {
            // Shouldn't happen if `prepareNext()` works correctly
            return; // @codeCoverageIgnore
        }

        $this->currentIterator->next();
        $this->counter++;

        while (!$this->currentIterator->valid() && $this->iterator->valid()) {
            $this->prepareNext();
        }
    }

    /**
     * Return the key of the current element
     *
     * @return string|int|null
     */
    public function key()
    {
        if (!isset($this->currentIterator) || !$this->currentIterator->valid()) {
            return null;
        }

        return $this->preserveKeys ? $this->currentIterator->key() : $this->counter;
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->currentIterator) && $this->currentIterator->valid();
    }

    /**
     * Rewind the iterator to the first element
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->iterator->rewind();
        $this->currentIterator = null;
        $this->counter = 0;

        $this->prepareNext();
    }

    /**
     * Returns the top iterator.
     *
     * @return \Iterator
     */
    public function getInnerIterator(): \Iterator
    {
        return $this->iterator;
    }
}
