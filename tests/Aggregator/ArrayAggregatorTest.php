<?php

namespace Jasny\IteratorPipeline\Tests\Aggregator;

use Jasny\IteratorPipeline\Aggregator\ArrayAggregator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\ArrayAggregator
 */
class ArrayAggregatorTest extends TestCase
{
    public function testAggregate()
    {
        $values = ['one', 'two', 'three'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new ArrayAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals($values, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateArrayable()
    {
        $values = ['one', 'two', 'three'];
        $array = \SplFixedArray::fromArray($values);

        $aggregator = new ArrayAggregator($array);

        $result = $aggregator();

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

        $aggregator = new ArrayAggregator($generator);

        $result = $aggregator();

        $this->assertEquals($values, $result);
    }

    public function testAggregateEmpty()
    {
        $aggregator = new ArrayAggregator(new \EmptyIterator());

        $result = $aggregator();

        $this->assertEquals([], $result);
    }
}
