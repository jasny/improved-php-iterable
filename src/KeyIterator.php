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
     * {@inheritdoc}
     */
    public function next()
    {
        $this->counter++;

        parent::next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->counter;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return parent::key();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->counter = 0;

        parent::rewind();
    }
}
