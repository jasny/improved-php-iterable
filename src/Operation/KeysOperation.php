<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Operation;

/**
 * Use the keys as values.
 * @see array_keys
 */
class KeysOperation extends AbstractOperation
{
    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            yield $key;
        }
    }
}
