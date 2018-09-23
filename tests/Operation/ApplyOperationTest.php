<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Operation\ApplyOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\ApplyOperation
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class ApplyOperationTest extends TestCase
{
    public function testIterate()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $iterator = new ApplyOperation($objects, function($value, $key) {
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

    public function testIterateIterator()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $inner = new \ArrayIterator($objects);

        $iterator = new ApplyOperation($inner, function($value, $key) {
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

        $iterator = new ApplyOperation($inner, function($value, $key) {
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
        $iterator = new ApplyOperation(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
