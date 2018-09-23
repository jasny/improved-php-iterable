<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Walk through all sub-iterables (array, Iterator or IteratorAggregate) and concatenate them.
 */
class FlattenOperation extends AbstractOperation
{
    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @var bool
     */
    protected $preserveKeys;


    /**
     * Class constructor.
     *
     * @param iterable $input
     * @param bool     $preserveKeys
     */
    public function __construct(iterable $input, bool $preserveKeys = false)
    {
        parent::__construct($input);

        $this->preserveKeys = $preserveKeys;
    }

    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Traversable
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $topKey => $element) {
            if (!is_iterable($element)) {
                yield ($this->preserveKeys ? $topKey : $this->counter++) => $element;
                continue;
            }

            foreach ($element as $key => $item) {
                yield ($this->preserveKeys ? $key : $this->counter++) => $item;
            }
        }
    }
}
