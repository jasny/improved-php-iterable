<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Use values as keys and visa versa.
 * @see array_flip
 */
class FlipOperation extends AbstractOperation
{
    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            yield $value => $key;
        }
    }
}
