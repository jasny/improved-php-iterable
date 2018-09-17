<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Map all keys of an Iterator.
 */
class MapKeysIterator extends \IteratorIterator
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * Constructor.
     *
     * @param \Iterator $iterator
     * @param callable $callable
     */
    public function __construct(\Iterator $iterator, callable $callable)
    {
        parent::__construct($iterator);

        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return call_user_func($this->callable, parent::key(), parent::current());
    }
}
