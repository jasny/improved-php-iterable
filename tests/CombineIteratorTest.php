<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\CombineIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\CombineIterator
 * @covers \Jasny\Iterator\TraversableIteratorTrait
 */
class CombineIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $keysInner = new \ArrayIterator(array_keys($values));
        $valuesInner = new \ArrayIterator(array_values($values));

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result);
    }

    public function testIterateIgnoreKeys()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $keysInner = new \ArrayIterator(array_keys($values));
        $valuesInner = new \ArrayIterator($values);

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result);
    }

    public function testIterateNotUnique()
    {
        $keys = ['foo', 'bar', 'qux', 'foo'];
        $values = ['one', 'two', 'three', 'four'];

        $keysInner = new \ArrayIterator($keys);
        $valuesInner = new \ArrayIterator($values);

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame($keys, $resultKeys);
        $this->assertSame($values, $resultValues);
    }

    public function testIterateMixedKeys()
    {
        $keys = [null, new \stdClass(), ['hello', 'world'], 5.2];
        $values = ['one', 'two', 'three', 'four'];

        $keysInner = new \ArrayIterator($keys);
        $valuesInner = new \ArrayIterator($values);

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame($keys, $resultKeys);
        $this->assertSame($values, $resultValues);
    }

    public function testIterateLesKeys()
    {
        $keys = ['foo', 'bar', 'qux'];
        $values = ['one', 'two', 'three', 'four', 'five'];

        $keysInner = new \ArrayIterator($keys);
        $valuesInner = new \ArrayIterator($values);

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $result = iterator_to_array($iterator);

        $expected = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three'];
        $this->assertSame($expected, $result);
    }

    public function testIterateLessValues()
    {
        $keys = ['foo', 'bar', 'qux', 'zoo', 'wut'];
        $values = ['one', 'two', 'three'];

        $keysInner = new \ArrayIterator($keys);
        $valuesInner = new \ArrayIterator($values);

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $result = iterator_to_array($iterator);

        $expected = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three', 'zoo' => null, 'wut' => null];
        $this->assertSame($expected, $result);
    }

    public function testIterateArrayObject()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $keysInner = new \ArrayObject(array_keys($values));
        $valuesInner = new \ArrayObject(array_values($values));

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new CombineIterator(new \EmptyIterator(), new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
