<?php

namespace Jasny\IteratorProjection\Tests;

use Jasny\IteratorProjection\Operation\UniqueOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorProjection\Operation\UniqueOperation
 */
class UniqueOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = ['foo', 'bar', 'qux', 'foo', 'zoo', 'foo', 'bar'];

        $iterator = new UniqueOperation($values);

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo', 1 => 'bar', 2 => 'qux', 4 => 'zoo'], $result);
    }

    public function testIterateObjects()
    {
        $first = (object)[];
        $second = (object)[];
        $third = (object)[];

        $values = [$first, $second, $first, $third, $first, $second];

        $iterator = new UniqueOperation($values);

        $result = iterator_to_array($iterator);

        $this->assertSame([0 => $first, 1 => $second, 3 => $third], $result);
    }

    public function testIterateCallback()
    {
        $values = ['foo53', 'bar76', 'qux24', 'foo99', 'zoo34', 'foo22', 'bar11'];

        $iterator = new UniqueOperation($values, function($value) {
            return substr($value, 0, 3);
        });

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo53', 1 => 'bar76', 2 => 'qux24', 4 => 'zoo34'], $result);
    }

    public function testIterateIterator()
    {
        $values = ['foo', 'bar', 'qux', 'foo', 'zoo', 'foo', 'bar'];
        $inner = new \ArrayIterator($values);

        $iterator = new UniqueOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo', 1 => 'bar', 2 => 'qux', 4 => 'zoo'], $result);
    }

    public function testIterateArrayObject()
    {
        $values = ['foo', 'bar', 'qux', 'foo', 'zoo', 'foo', 'bar'];
        $inner = new \ArrayObject($values);

        $iterator = new UniqueOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo', 1 => 'bar', 2 => 'qux', 4 => 'zoo'], $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new UniqueOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
