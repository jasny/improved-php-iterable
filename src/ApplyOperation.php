<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Apply a callback to each element.
 */
class ApplyOperation extends \IteratorIterator
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
     * Get the current value
     *
     * @return mixed
     */
    public function current()
    {
        $item = parent::current();
        $key = parent::key();

        call_user_func($this->callable, $item, $key);

        return $item;
    }
}
