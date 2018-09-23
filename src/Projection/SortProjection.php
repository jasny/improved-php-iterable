<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Projection;

use function Jasny\expect_type;

/**
 * Sort all elements of an iterator.
 */
class SortProjection implements \IteratorAggregate
{
    /**
     * @var iterable
     */
    protected $input;

    /**
     * @var callable|null
     */
    protected $comparator;

    /**
     * @var int
     */
    protected $flags;

    /**
     * @var bool
     */
    protected $preserveKeys;


    /**
     * AbstractIterator constructor.
     *
     * @param iterable     $input
     * @param callable|int $comparator    SORT_* flags as binary set or callback comparator function
     * @param bool         $preserveKeys
     */
    public function __construct(iterable $input, $comparator = \SORT_REGULAR, bool $preserveKeys = false)
    {
        expect_type($comparator, ['callable', 'int'], "Expected comparator to be a callable or integer, %s given");

        $this->input = $input;
        $this->comparator = is_int($comparator) ? null : $comparator;
        $this->flags = is_int($comparator) ? $comparator : \SORT_REGULAR;
        $this->preserveKeys = $preserveKeys;
    }


    /**
     * Sort input values preserving the keys.
     *
     * @return \Generator
     */
    protected function sortPreverseKeys(): \Generator
    {
        $keys = [];
        $values = [];

        foreach ($this->input as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        if (isset($this->comparator)) {
            uasort($values, $this->comparator);
        } else {
            asort($values, $this->flags);
        }

        foreach ($values as $index => $value) {
            yield $keys[$index] => $value;
        }
    }

    /**
     * Sort input values dropping the keys.
     *
     * @return \ArrayIterator
     */
    protected function sortIgnoreKeys(): \ArrayIterator
    {
        $values = is_array($this->input) ? $this->input : iterator_to_array($this->input, false);

        if (isset($this->comparator)) {
            usort($values, $this->comparator);
        } else {
            sort($values, $this->flags);
        }

        return new \ArrayIterator($values);
    }


    /**
     * Get the iterator with sorted values
     *
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return $this->preserveKeys ? $this->sortPreverseKeys() : $this->sortIgnoreKeys();
    }
}
