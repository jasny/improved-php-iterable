<?php

namespace Jasny\Tests\Aggregator;

use PHPUnit\Framework\TestCase;
use Jasny\Aggregator\MaxAggregator;

/**
 * @covers \Jasny\Aggregator\AbstractAggregator
 * @covers \Jasny\Aggregator\MaxAggregator
 */
class MaxAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = [99, 24, 122];
        $iterator = new \ArrayIterator($values);

        $collector = new MaxAggregator($iterator);

        $result = $collector();

        $this->assertEquals(122, $result);
        $this->assertSame($iterator, $collector->getIterator());
    }

    public function testAggregateNegative()
    {
        $values = [99, 24, -7, -337, 122];
        $iterator = new \ArrayIterator($values);

        $collector = new MaxAggregator($iterator);

        $result = $collector();

        $this->assertEquals(122, $result);
        $this->assertSame($iterator, $collector->getIterator());
    }

    public function testAggregateFloat()
    {
        $values = [9.9, 99.1, 7.5, 8.0];
        $iterator = new \ArrayIterator($values);

        $collector = new MaxAggregator($iterator);

        $result = $collector();

        $this->assertEquals(99.1, $result);
    }

    public function testAggregateAlpha()
    {
        $values = ["Charlie", "Bravo", "Alpha", "Foxtrot", "Delta"];
        $iterator = new \ArrayIterator($values);

        $collector = new MaxAggregator($iterator);

        $result = $collector();

        $this->assertEquals("Foxtrot", $result);
    }

    public function testAggregateCallback()
    {
        $values = [
            (object)['num' => 1, 'name' => "Charlie"],
            (object)['num' => 2, 'name' => "Bravo"],
            (object)['num' => 3, 'name' => "Alpha"],
            (object)['num' => 4, 'name' => "Foxtrot"],
            (object)['num' => 5, 'name' => "Delta"],
            (object)['num' => 6, 'name' => "Alpha"]
        ];
        $iterator = new \ArrayIterator($values);

        $collector = new MaxAggregator($iterator, function(\stdClass $a, \stdClass $b) {
            return $a->name <=> $b->name;
        });

        $result = $collector();

        $this->assertSame($values[3], $result);
    }

    public function testAggregateEmpty()
    {
        $collector = new MaxAggregator(new \EmptyIterator());

        $result = $collector();

        $this->assertNull($result);
    }
}
