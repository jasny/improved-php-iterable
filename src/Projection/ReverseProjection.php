<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Projection;

use Jasny\IteratorProjection\Iterator\CombineIterator;

/**
 * Reverse order of elements of an iterator.
 */
class ReverseProjection implements \IteratorAggregate
{
    /**
     * @var iterable
     */
    protected $input;

    /**
     * @var bool
     */
    protected $preserveKeys;


    /**
     * AbstractIterator constructor.
     *
     * @param iterable $input
     * @param bool     $preserveKeys
     */
    public function __construct(iterable $input, bool $preserveKeys = false)
    {
        $this->input = $input;
        $this->preserveKeys = $preserveKeys;
    }

    /**
     * Get new iterator.
     *
     * @return \Iterator
     */
    public function getIterator(): \Traversable
    {
        $keys = [];
        $values = [];

        foreach ($this->input as $key => $value) {
            if ($this->preserveKeys) {
                array_unshift($keys, $key);
            }

            array_unshift($values, $value);
        }

        return $this->preserveKeys ? new CombineIterator($keys, $values) : new \ArrayIterator($values);
    }
}
