<?php declare(strict_types=1);

namespace Improved\Iterator;

use function Improved\iterable_to_iterator;

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
        $this->keys = iterable_to_iterator($keys);
        $this->values = iterable_to_iterator($values);
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
