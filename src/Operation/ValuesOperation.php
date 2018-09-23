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
     * Apply logic.
     *
     * @return \ArrayIterator
     */
    protected function apply(): \Traversable
    {
        $array = is_array($this->input)
            ? array_values($this->input)
            : iterator_to_array($this->input, false);

        return new \ArrayIterator($array);
    }
}
