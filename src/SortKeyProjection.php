<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Projection;

use function Jasny\expect_type;

/**
 * Sort all elements of an iterator based on the key.
 */
class SortKeyProjection implements \IteratorAggregate
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
     * AbstractIterator constructor.
     *
     * @param iterable     $input
     * @param callable|int $comparator  SORT_* flags as binary set or callback comparator function
     */
    public function __construct(iterable $input, $comparator = \SORT_REGULAR)
    {
        expect_type($comparator, ['callable', 'int'], "Expected comparator to be a callable or integer, %s given");

        $this->input = $input;
        $this->comparator = is_int($comparator) ? null : $comparator;
        $this->flags = is_int($comparator) ? $comparator : \SORT_REGULAR;
    }


    /**
     * Get the iterator with sorted values
     *
     * @return \Generator
     */
    public function getIterator(): \Iterator
    {
        $keys = [];
        $values = [];

        foreach ($this->input as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        isset($this->comparator)
            ? uasort($keys, $this->comparator)
            : asort($keys, $this->flags);

        foreach ($keys as $index => $key) {
            yield $key => $values[$index];
        }
    }
}
