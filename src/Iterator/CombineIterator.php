<?php

declare(strict_types=1);

namespace Improved\Iterator;

use Iterator;

use function Improved\iterable_to_iterator;

/**
 * Iterator through keys and values, where key may be any type.
 *
 * @template TKey
 * @template TValue
 * @implements Iterator<TKey, TValue>
 */
class CombineIterator implements Iterator
{
    /**
     * @var Iterator<TKey>
     */
    protected Iterator $keys;

    /**
     * @var Iterator<TValue>
     */
    protected Iterator $values;

    /**
     * Class constructor.
     *
     * @param iterable<TKey> $keys
     * @param iterable<TValue> $values
     */
    public function __construct(iterable $keys, iterable $values)
    {
        $this->keys = iterable_to_iterator($keys);
        $this->values = iterable_to_iterator($values);
    }

    /**
     * Get the current value.
     *
     * @return TValue
     */
    public function current(): mixed
    {
        return $this->values->current();
    }

    /**
     * Get the current key.
     *
     * @return TKey
     */
    public function key(): mixed
    {
        return $this->keys->current();
    }

    /**
     * Forward to the next element.
     */
    public function next(): void
    {
        $this->keys->next();
        $this->values->next();
    }

    /**
     * Checks if the iterator is valid.
     */
    public function valid(): bool
    {
        return $this->keys->valid();
    }

    /**
     * Checks if the iterator is valid.
     */
    public function rewind(): void
    {
        $this->keys->rewind();
        $this->values->rewind();
    }
}
