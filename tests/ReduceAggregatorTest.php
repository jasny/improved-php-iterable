<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Aggregator\ReduceAggregator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\ReduceAggregator
 */
class ReduceAggregatorTest extends TestCase
{
    public function testAggregate()
    {
        $values = [2, 3, 4];
        $iterator = new \ArrayIterator($values);

        $aggregator = new ReduceAggregator($iterator, function ($product, $value) {
            return $product * $value;
        }, 1);

        $result = $aggregator();

        $this->assertEquals(24, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateEmpty()
    {
        $aggregator = new ReduceAggregator(new \EmptyIterator(), function ($product, $value) {
            return $product * $value;
        }, 1);

        $result = $aggregator();

        $this->assertEquals(1, $result);
    }
}
