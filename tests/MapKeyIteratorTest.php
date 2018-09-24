<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Operation\MapKeyOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\iterablemapkeys
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class MapKeyIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = range(1, 4);

        $iterator = new MapKeyOperation($values, function($key) {
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
        $values = ['one' => 'foo', 'two' => 'bar', 'three' => 'qux'];

        $iterator = new MapKeyOperation($values, function($key, $value) {
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

    public function testIterateIterator()
    {
        $inner = new \ArrayIterator(range(1, 4));

        $iterator = new MapKeyOperation($inner, function($key) {
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

    public function testIterateArrayObject()
    {
        $inner = new \ArrayObject(range(1, 4));

        $iterator = new MapKeyOperation($inner, function($key) {
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
        $iterator = new MapKeyOperation(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
