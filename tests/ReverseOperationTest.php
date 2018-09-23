<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\ReverseOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\ArrayIteratorAggregateTrait
 * @covers \Jasny\Iterator\ReverseOperation
 */
class ReverseOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = range(3, 12);
        $inner = new \ArrayIterator($values);

        $iterator = new ReverseOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);

        $this->assertEquals($values, iterator_to_array($inner), "Original iterator should not be changed");
    }

    public function testIterateGenerator()
    {
        $values = range(3, 12);

        $loop = function($values) {
            foreach ($values as $value) {
                yield $value;
            }
        };

        $generator = $loop($values);
        $iterator = new ReverseOperation($generator);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);
    }

    public function testIterateArrayable()
    {
        $values = range(3, 12);

        $inner = \SplFixedArray::fromArray($values);
        $iterator = new ReverseOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);
    }

    public function testIterateArrayObject()
    {
        $values = range(3, 12);

        $inner = new \ArrayObject($values);
        $iterator = new ReverseOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new ReverseOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
