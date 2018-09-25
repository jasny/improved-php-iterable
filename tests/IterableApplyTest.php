<?php

declare(strict_types=1);

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_apply;

/**
 * @covers \Jasny\iterable_apply
 */
class IterableApplyTest extends TestCase
{
    use LazyExecutionIteratorTrait;

    public function test()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $iterator = iterable_apply($objects, function($value, $key) {
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

    public function testIterator()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $inner = new \ArrayIterator($objects);

        $iterator = iterable_apply($inner, function($value, $key) {
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

    public function testArrayObject()
    {
        $objects = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass(),
            'qux' => new \stdClass()
        ];

        $inner = new \ArrayObject($objects);

        $iterator = iterable_apply($inner, function($value, $key) {
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

    public function testEmpty()
    {
        $iterator = iterable_apply(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_apply($iterator, function() {});

        $this->assertTrue(true, "No warning");
    }
}
