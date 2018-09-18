<?php

namespace Jasny\Tests\Aggregator;

use PHPUnit\Framework\TestCase;
use Jasny\Aggregator\ConcatAggregator;

/**
 * @covers \Jasny\Aggregator\AbstractAggregator
 * @covers \Jasny\Aggregator\ConcatAggregator
 */
class ConcatAggregatorTest extends TestCase
{
    public function testAggregate()
    {
        $values = ['a', 'b', 'c', 'd'];
        $iterator = new \ArrayIterator($values);

        $collector = new ConcatAggregator($iterator);

        $result = $collector();

        $this->assertEquals('abcd', $result);
        $this->assertSame($iterator, $collector->getIterator());
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

        $collector = new ConcatAggregator($iterator);

        $result = $collector();

        $this->assertEquals('1ring2bind', $result);
    }

    public function testAggregateGlue()
    {
        $values = ['one', 'ring', 'to', 'bind'];
        $iterator = new \ArrayIterator($values);

        $collector = new ConcatAggregator($iterator, '<->');

        $result = $collector();

        $this->assertEquals('one<->ring<->to<->bind', $result);
    }

    public function testAggregateEmpty()
    {
        $collector = new ConcatAggregator(new \EmptyIterator());

        $result = $collector();

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

        $collector = new ConcatAggregator($iterator);

        $collector();
    }
}
