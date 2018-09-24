<?php

namespace Jasny\IteratorPipeline\Tests;

use PHPUnit\Framework\TestCase;
use Jasny\IteratorPipeline\Aggregator\SumAggregator;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\SumAggregator
 */
class SumAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = [10, 99, 24, 122];
        $iterator = new \ArrayIterator($values);

        $aggregator = new SumAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(255, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateFloat()
    {
        $values = [7.5, 99.1, 8];
        $iterator = new \ArrayIterator($values);

        $aggregator = new SumAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(114.6, $result);
    }

    public function testAggregateEmpty()
    {
        $aggregator = new SumAggregator(new \EmptyIterator());

        $result = $aggregator();

        $this->assertEquals(0, $result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage All elements should be an int or float, string given
     */
    public function testAggregateInvalidArgument()
    {
        $values = ['hello', 'world'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new SumAggregator($iterator);

        $aggregator();
    }
}
