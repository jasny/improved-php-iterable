<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\MapKeyIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\MapKeyIterator
 */
class MapKeyIteratorTest extends TestCase
{
    public function testIterate()
    {
        $inner = new \ArrayIterator(range(1, 4));

        $iterator = new MapKeyIterator($inner, function($key) {
            return str_repeat('*', $key);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            '' => 1,
            '*' => 2,
            '**' => 3,
            '***' => 4
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateKeyValue()
    {
        $inner = new \ArrayIterator(['one' => 'foo', 'two' => 'bar', 'three' => 'qux']);

        $iterator = new MapKeyIterator($inner, function($key, $value) {
            return "$key-$value";
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one-foo' => 'foo',
            'two-bar' => 'bar',
            'three-qux' => 'qux'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateArrayObject()
    {
        $inner = new \ArrayObject(range(1, 4));

        $iterator = new MapKeyIterator($inner, function($key) {
            return str_repeat('*', $key);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            '' => 1,
            '*' => 2,
            '**' => 3,
            '***' => 4
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new MapKeyIterator(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
