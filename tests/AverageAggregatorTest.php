<?php

namespace Jasny\Tests\Aggregator;

use PHPUnit\Framework\TestCase;
use Jasny\Aggregator\AverageAggregator;

/**
 * @covers \Jasny\Aggregator\AbstractAggregator
 * @covers \Jasny\Aggregator\AverageAggregator
 */
class AverageAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = [10, 99, 24, 122];
        $iterator = new \ArrayIterator($values);

        $collector = new AverageAggregator($iterator);

        $result = $collector();

        $this->assertEquals(63.75, $result);
        $this->assertSame($iterator, $collector->getIterator());
    }

    public function testAggregateFloat()
    {
        $values = [7.5, 99.1, 8];
        $iterator = new \ArrayIterator($values);

        $collector = new AverageAggregator($iterator);

        $result = $collector();

        $this->assertEquals(38.2, $result);
    }

    public function testAggregateEmpty()
    {
        $collector = new AverageAggregator(new \EmptyIterator());

        $result = $collector();

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

        $collector = new AverageAggregator($iterator);

        $collector();
    }
}
