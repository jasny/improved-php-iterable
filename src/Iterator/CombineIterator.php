<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Iterator;

/**
 * Iterator through keys and values, where key may be any type.
 */
class CombineIterator implements \Iterator
{
    /**
     * @var \Iterator
     */
    protected $keys;

    /**
     * @var \Iterator
     */
    protected $values;

    /**
     * Class constructor.
     *
     * @param iterable $keys
     * @param iterable $values
     */
    public function __construct(iterable $keys, iterable $values)
    {
        $this->keys = $this->iterableToIterator($keys);
        $this->values = $this->iterableToIterator($values);
    }

    /**
     * Turn any Traversable into an Iterator.
     *
     * @param iterable $iterable
     * @return \Iterator
     */
    public function iterableToIterator(iterable $iterable): \Iterator
    {
        if (is_array($iterable)) {
            return new \ArrayIterator($iterable);
        }

        if ($iterable instanceof \IteratorAggregate) {
            $iterable = $iterable->getIterator();
        }

        if (!$iterable instanceof \Iterator) {
            $iterable = new \IteratorIterator($iterable);
        }

        return $iterable;
    }

    /**
     * Get the current value.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->values->current();
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
        $this->keys->next();
        $this->values->next();
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
        $this->keys->rewind();
        $this->values->rewind();
    }
}
