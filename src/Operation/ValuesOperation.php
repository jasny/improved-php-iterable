<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Drop the keys, only keep the values.
 * @see array_values
 */
class ValuesOperation extends AbstractOperation
{
    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $value) {
            yield $value;
        }
    }
}
