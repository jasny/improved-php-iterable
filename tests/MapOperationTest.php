<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\MapOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\MapOperation
 */
class MapOperationTest extends TestCase
{
    public function testIterate()
    {
        $inner = new \ArrayIterator(range(1, 4));

        $iterator = new MapOperation($inner, function($value) {
            return str_repeat('*', $value);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            '*',
            '**',
            '***',
            '****'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateKeyValue()
    {
        $inner = new \ArrayIterator(['one' => 'foo', 'two' => 'bar', 'three' => 'qux']);

        $iterator = new MapOperation($inner, function($value, $key) {
            return "$key-$value";
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one' => 'one-foo',
            'two' => 'two-bar',
            'three' => 'three-qux'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateArrayObject()
    {
        $inner = new \ArrayObject(range(1, 4));

        $iterator = new MapOperation($inner, function($value) {
            return str_repeat('*', $value);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            '*',
            '**',
            '***',
            '****'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new MapOperation(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
