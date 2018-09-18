<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\ApplyIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\ApplyIterator
 */
class ApplyIteratorTest extends TestCase
{
    public function testIterate()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $inner = new \ArrayIterator($objects);

        $iterator = new ApplyIterator($inner, function($value, $key) {
            $value->key = $key;
            return 10; // Should be ignored
        });

        $this->assertObjectNotHasAttribute('key', $objects['foo']);

        $result = iterator_to_array($iterator);

        $this->assertSame($objects, $result);

        $this->assertAttributeEquals('foo', 'key', $objects['foo']);
        $this->assertAttributeEquals('bar', 'key', $objects['bar']);
        $this->assertAttributeEquals('qux', 'key', $objects['qux']);
    }

    public function testIterateArrayObject()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $inner = new \ArrayObject($objects);

        $iterator = new ApplyIterator($inner, function($value, $key) {
            $value->key = $key;
            return 10; // Should be ignored
        });

        $this->assertObjectNotHasAttribute('key', $objects['foo']);

        $result = iterator_to_array($iterator);

        $this->assertSame($objects, $result);

        $this->assertAttributeEquals('foo', 'key', $objects['foo']);
        $this->assertAttributeEquals('bar', 'key', $objects['bar']);
        $this->assertAttributeEquals('qux', 'key', $objects['qux']);
    }

    public function testIterateEmpty()
    {
        $iterator = new ApplyIterator(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
