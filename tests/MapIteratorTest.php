<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\MapIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\MapIterator
 */
class MapIteratorTest extends TestCase
{
    public function testIterate()
    {
        $inner = new \ArrayIterator(range(1, 4));

        $iterator = new MapIterator($inner, function($value) {
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

    public function testIterateKey()
    {
        $inner = new \ArrayIterator(['one' => 'foo', 'two' => 'bar', 'three' => 'qux']);

        $iterator = new MapIterator($inner, function($value, $key) {
            return "$key = $value";
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one' => 'one = foo',
            'two' => 'two = bar',
            'three' => 'three = qux'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new MapIterator(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
