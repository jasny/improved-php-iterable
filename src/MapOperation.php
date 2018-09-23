<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Map all elements of an Iterator.
 */
class MapOperation extends \IteratorIterator
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
     * Get the current element
     *
     * @return mixed
     */
    public function current()
    {
        return call_user_func($this->callable, parent::current(), parent::key());
    }
}
