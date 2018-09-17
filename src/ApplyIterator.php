<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Apply a callback to each element.
 */
class ApplyIterator extends \IteratorIterator
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * Constructor.
     *
     * @param \Iterator $iterator
     * @param callable  $callable
     */
    public function __construct(\Iterator $iterator, callable $callable)
    {
        parent::__construct($iterator);

        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $item = parent::current();
        $key = parent::key();

        call_user_func($this->callable, $item, $key);

        return $item;
    }
}
