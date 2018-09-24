<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Aggregator;

use function Jasny\expect_type;

/**
 * Aggregator that produces the arithmetic mean.
 */
class AverageAggregator extends AbstractAggregator
{
    /**
     * Invoke the aggregator.
     *
     * @return float
     * @throws \UnexpectedValueException if not all values are integers or floats
     */
    public function __invoke(): float
    {
        $count = 0;
        $sum = 0;

        foreach ($this->iterator as $item) {
            expect_type(
                $item,
                ['int', 'float'],
                \UnexpectedValueException::class,
                "All elements should be an int or float, %s given"
            );

            $count++;
            $sum += $item;
        }

        return $count == 0 ? \NAN : ($sum / $count);
    }
}
