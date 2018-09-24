<?php

namespace Jasny\IteratorPipeline\Tests;

use PHPUnit\Framework\TestCase;
use Jasny\IteratorPipeline\Aggregator\MinAggregator;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\MinAggregator
 */
class MinAggregatorTest extends TestCase
{
    public function testAggregateInt()
    {
        $values = [99, 24, 122];
        $iterator = new \ArrayIterator($values);

        $aggregator = new MinAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(24, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateNegative()
    {
        $values = [99, 24, -7, -337, 122];
        $iterator = new \ArrayIterator($values);

        $aggregator = new MinAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(-337, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateFloat()
    {
        $values = [9.9, 99.1, 7.5, 8.0];
        $iterator = new \ArrayIterator($values);

        $aggregator = new MinAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals(7.5, $result);
    }

    public function testAggregateAlpha()
    {
        $values = ["Charlie", "Bravo", "Alpha", "Foxtrot", "Delta"];
        $iterator = new \ArrayIterator($values);

        $aggregator = new MinAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals("Alpha", $result);
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

        $aggregator = new MinAggregator($iterator, function(\stdClass $a, \stdClass $b) {
            return $a->name <=> $b->name;
        });

        $result = $aggregator();

        $this->assertSame($values[2], $result);
    }

    public function testAggregateEmpty()
    {
        $aggregator = new MinAggregator(new \EmptyIterator());

        $result = $aggregator();

        $this->assertNull($result);
    }
}
