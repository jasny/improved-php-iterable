<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Use values as keys and visa versa.
 * @see array_flip
 */
class FlipOperation extends \IteratorIterator
{
    /**
     * Get the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return parent::key();
    }

    /**
     * Get the key of the current element.
     *
     * @return mixed
     */
    public function key()
    {
        return parent::current();
    }
}
