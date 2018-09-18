<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Iterator through keys and values, where key may be any type.
 */
class CombineIterator extends \IteratorIterator
{
    /**
     * @var \Iterator
     */
    protected $keys;

    /**
     * Class constructor.
     *
     * @param \Iterator    $keys
     * @param \Traversable $values
     */
    public function __construct(\Iterator $keys, \Traversable $values)
    {
        parent::__construct($values);

        $this->keys = $keys;
    }

    /**
     * Get the current key.
     *
     * @return mixed
     */
    public function key()
    {
        return $this->keys->current();
    }

    /**
     * Forward to the next element.
     *
     * @return void
     */
    public function next(): void
    {
        parent::next();

        $this->keys->next();
    }

    /**
     * Checks if the iterator is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->keys->valid();
    }

    /**
     * Checks if the iterator is valid.
     *
     * @return void
     */
    public function rewind(): void
    {
        parent::rewind();

        $this->keys->rewind();
    }
}
