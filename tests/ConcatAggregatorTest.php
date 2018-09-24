<?php

namespace Jasny\IteratorPipeline\Tests;

use PHPUnit\Framework\TestCase;
use Jasny\IteratorPipeline\Aggregator\ConcatAggregator;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\ConcatAggregator
 */
class ConcatAggregatorTest extends TestCase
{
    public function testAggregate()
    {
        $values = ['a', 'b', 'c', 'd'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new ConcatAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals('abcd', $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateMixed()
    {
        $bind = new class() {
            public function __toString(): string
            {
                return 'bind';
            }
        };

        $values = [1, 'ring', 2, $bind];
        $iterator = new \ArrayIterator($values);

        $aggregator = new ConcatAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals('1ring2bind', $result);
    }

    public function testAggregateGlue()
    {
        $values = ['one', 'ring', 'to', 'bind'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new ConcatAggregator($iterator, '<->');

        $result = $aggregator();

        $this->assertEquals('one<->ring<->to<->bind', $result);
    }

    public function testAggregateEmpty()
    {
        $aggregator = new ConcatAggregator(new \EmptyIterator());

        $result = $aggregator();

        $this->assertEquals('', $result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage All elements should be usable as string, stdClass object given
     */
    public function testAggregateInvalidArgument()
    {
        $values = ['one', new \stdClass()];
        $iterator = new \ArrayIterator($values);

        $aggregator = new ConcatAggregator($iterator);

        $aggregator();
    }
}
