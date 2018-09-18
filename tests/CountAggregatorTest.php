<?php

namespace Jasny\Tests\Aggregator;

use PHPUnit\Framework\TestCase;
use Jasny\Aggregator\CountAggregator;

/**
 * @covers \Jasny\Aggregator\AbstractAggregator
 * @covers \Jasny\Aggregator\CountAggregator
 */
class CountAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = array_fill(2, 6, null);
        $iterator = new \ArrayIterator($values);

        $collector = new CountAggregator($iterator);

        $result = $collector();

        $this->assertEquals(6, $result);
        $this->assertSame($iterator, $collector->getIterator());
    }

    public function testAggregateEmpty()
    {
        $collector = new CountAggregator(new \EmptyIterator());

        $result = $collector();

        $this->assertEquals(0, $result);
    }
}
