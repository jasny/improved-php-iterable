<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Map all elements of an Iterator.
 */
class MapIterator extends \IteratorIterator
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
        return call_user_func($this->callable, parent::current(), parent::key());
    }
}
