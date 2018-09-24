<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

use function Jasny\expect_type;

/**
 * Aggregator that produces the sum of a numbers,
 * If no elements are present, the result is 0.
 */
class iterablesum extends AbstractAggregator
{
    /**
     * Invoke the aggregator.
     *
     * @return int|float
     * @throws \UnexpectedValueException if not all values are integers or floats
     */
    public function __invoke()
    {
        $sum = 0;

        foreach ($iterator as $item) {
            expect_type(
                $item,
                ['int', 'float'],
                \UnexpectedValueException::class,
                "All elements should be an int or float, %s given"
            );

            $sum += $item;
        }

        return $sum;
    }
}
