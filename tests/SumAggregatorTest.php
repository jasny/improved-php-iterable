<?php

namespace Jasny\Tests\Aggregator;

use PHPUnit\Framework\TestCase;
use Jasny\Aggregator\SumAggregator;

/**
 * @covers \Jasny\Aggregator\AbstractAggregator
 * @covers \Jasny\Aggregator\SumAggregator
 */
class SumAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = [10, 99, 24, 122];
        $iterator = new \ArrayIterator($values);

        $collector = new SumAggregator($iterator);

        $result = $collector();

        $this->assertEquals(255, $result);
        $this->assertSame($iterator, $collector->getIterator());
    }

    public function testAggregateFloat()
    {
        $values = [7.5, 99.1, 8];
        $iterator = new \ArrayIterator($values);

        $collector = new SumAggregator($iterator);

        $result = $collector();

        $this->assertEquals(114.6, $result);
    }

    public function testAggregateEmpty()
    {
        $collector = new SumAggregator(new \EmptyIterator());

        $result = $collector();

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

        $collector = new SumAggregator($iterator);

        $collector();
    }
}
