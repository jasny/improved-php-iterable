<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Map all keys of an Iterator.
 */
class MapKeyIterator extends \IteratorIterator
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * Constructor.
     *
     * @param \Traversable $iterator
     * @param callable     $callable
     */
    public function __construct(\Traversable $iterator, callable $callable)
    {
        parent::__construct($iterator);

        $this->callable = $callable;
    }

    /**
     * Get the key of the current element.
     *
     * @return mixed
     */
    public function key()
    {
        return call_user_func($this->callable, parent::key(), parent::current());
    }
}
