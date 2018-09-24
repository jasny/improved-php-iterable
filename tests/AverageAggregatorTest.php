<?php

namespace Jasny\IteratorPipeline\Tests;

use PHPUnit\Framework\TestCase;
use Jasny\IteratorPipeline\Aggregator\AverageAggregator;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\AverageAggregator
 */
class AverageAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = [10, 99, 24, 122];
        $iterator = new \ArrayIterator($values);

        $aggregator = new AverageAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(63.75, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateFloat()
    {
        $values = [7.5, 99.1, 8];
        $iterator = new \ArrayIterator($values);

        $aggregator = new AverageAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(38.2, $result);
    }

    public function testAggregateEmpty()
    {
        $aggregator = new AverageAggregator(new \EmptyIterator());

        $result = $aggregator();

        $this->assertNan($result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage All elements should be an int or float, string given
     */
    public function testAggregateInvalidArgument()
    {
        $values = ['hello', 'world'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new AverageAggregator($iterator);

        $aggregator();
    }
}
