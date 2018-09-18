<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\UniqueIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\TraversableIteratorTrait
 * @covers \Jasny\Iterator\UniqueIterator
 */
class UniqueIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = ['foo', 'bar', 'qux', 'foo', 'zoo', 'foo', 'bar'];
        $inner = new \ArrayIterator($values);

        $iterator = new UniqueIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo', 1 => 'bar', 2 => 'qux', 4 => 'zoo'], $result);
    }

    public function testIterateObjects()
    {
        $first = (object)[];
        $second = (object)[];
        $third = (object)[];

        $inner = new \ArrayIterator([$first, $second, $first, $third, $first, $second]);

        $iterator = new UniqueIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame([0 => $first, 1 => $second, 3 => $third], $result);
    }

    public function testIterateCallback()
    {
        $values = ['foo53', 'bar76', 'qux24', 'foo99', 'zoo34', 'foo22', 'bar11'];

        $inner = new \ArrayIterator($values);

        $iterator = new UniqueIterator($inner, function($value) {
            return substr($value, 0, 3);
        });

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo53', 1 => 'bar76', 2 => 'qux24', 4 => 'zoo34'], $result);
    }

    public function testIterateObject()
    {
        $values = ['foo', 'bar', 'qux', 'foo', 'zoo', 'foo', 'bar'];
        $inner = new \ArrayObject($values);

        $iterator = new UniqueIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo', 1 => 'bar', 2 => 'qux', 4 => 'zoo'], $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new UniqueIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
