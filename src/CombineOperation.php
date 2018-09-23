<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Iterator through keys and values, where key may be any type.
 */
class CombineOperation extends \IteratorIterator
{
    use TraversableIteratorTrait;

    /**
     * @var \Iterator
     */
    protected $keys;

    /**
     * Class constructor.
     *
     * @param \Traversable $keys
     * @param \Traversable $values
     */
    public function __construct(\Traversable $keys, \Traversable $values)
    {
        parent::__construct($values);

        $this->keys = $this->traverableToIterator($keys);
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
