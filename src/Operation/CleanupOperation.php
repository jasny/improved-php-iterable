<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Filter out null elements from iterator.
 */
class CleanupOperation extends AbstractOperation
{
    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            if (isset($value)) {
                yield $key => $value;
            }
        }
    }
}
