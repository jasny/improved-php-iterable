<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Use the keys as values.
 * @see array_keys
 */
class KeyIterator extends \IteratorIterator
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
    public function next()
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
     * Get the current element
     *
     * @return mixed
     */
    public function current()
    {
        return parent::key();
    }

    /**
     * Rewind to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        $this->counter = 0;

        parent::rewind();
    }
}
