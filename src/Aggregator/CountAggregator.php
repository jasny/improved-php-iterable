<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Aggregator that produces a count.
 */
class CountAggregator extends AbstractAggregator
{
    /**
     * Invoke the aggregator.
     *
     * @return int
     */
    public function __invoke(): int
    {
        return $this->iterator instanceof \Countable ? count($this->iterator) : iterator_count($this->iterator);
    }
}
