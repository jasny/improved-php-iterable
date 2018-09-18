<?php

namespace Jasny\Tests\Aggregator;

use Jasny\Aggregator\ArrayAggregator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Aggregator\AbstractAggregator
 * @covers \Jasny\Aggregator\ArrayAggregator
 */
class ArrayAggregatorTest extends TestCase
{
    public function testAggregate()
    {
        $values = ['one', 'two', 'three'];
        $iterator = new \ArrayIterator($values);

        $collector = new ArrayAggregator($iterator);

        $result = $collector();

        $this->assertEquals($values, $result);
        $this->assertSame($iterator, $collector->getIterator());
    }

    public function testAggregateArrayable()
    {
        $values = ['one', 'two', 'three'];
        $array = \SplFixedArray::fromArray($values);

        $collector = new ArrayAggregator($array);

        $result = $collector();

        $this->assertEquals($values, $result);
    }

    public function testAggregateGenerator()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        $fn = function($values): \Generator {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };
        $generator = $fn($values);

        $collector = new ArrayAggregator($generator);

        $result = $collector();

        $this->assertEquals($values, $result);
    }

    public function testAggregateEmpty()
    {
        $collector = new ArrayAggregator(new \EmptyIterator());

        $result = $collector();

        $this->assertEquals([], $result);
    }
}
