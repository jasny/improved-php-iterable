<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Drop the keys, only keep the values.
 * @see array_values
 */
class ValueIterator extends \IteratorIterator
{
    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * Forward to the next element.
     *
     * @return void
     */
    public function next(): void
    {
        $this->counter++;

        parent::next();
    }

    /**
     * Get the key of the current element.
     *
     * @return int
     */
    public function key(): int
    {
        return $this->counter;
    }

    /**
     * Rewind to the first element.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->counter = 0;

        parent::rewind();
    }
}
