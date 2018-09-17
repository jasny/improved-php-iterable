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
    public function rewind()
    {
        $this->counter = 0;

        parent::rewind();
    }
}
