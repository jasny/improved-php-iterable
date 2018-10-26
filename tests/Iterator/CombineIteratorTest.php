<?php declare(strict_types=1);

namespace Improved\Tests\Iterator;

use Improved\Iterator\CombineIterator;
use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\Iterator\CombineIterator
 */
class CombineIteratorTest extends TestCase
{
    use LazyExecutionIteratorTrait;

    public function testIterate()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $keys = array_keys($assoc);
        $values = array_values($assoc);

        $iterator = new CombineIterator($keys, $values);

        $result = iterator_to_array($iterator);

        $this->assertSame($assoc, $result);
    }

    public function testIterateIgnoreKeys()
    {
        $keys = ['I', 'II', 'III', 'IV', 'V'];
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $iterator = new CombineIterator($keys, $values);

        $result = iterator_to_array($iterator);

        $expected = ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'cuatro', 'V' => 'cinco'];
        $this->assertSame($expected, $result);
    }

    public function testIterateNotUnique()
    {
        $keys = ['foo', 'bar', 'qux', 'foo'];
        $values = ['one', 'two', 'three', 'four'];

        $iterator = new CombineIterator($keys, $values);

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

        $iterator = new CombineIterator($keys, $values);

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

        $iterator = new CombineIterator($keys, $values);

        $result = iterator_to_array($iterator);

        $expected = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three'];
        $this->assertSame($expected, $result);
    }

    public function testIterateLessValues()
    {
        $keys = ['foo', 'bar', 'qux', 'zoo', 'wut'];
        $values = ['one', 'two', 'three'];

        $iterator = new CombineIterator($keys, $values);

        $result = iterator_to_array($iterator);

        $expected = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three', 'zoo' => null, 'wut' => null];
        $this->assertSame($expected, $result);
    }

    public function testIterateIterator()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $keysInner = new \ArrayIterator(array_keys($assoc));
        $valuesInner = new \ArrayIterator(array_values($assoc));

        $iterator = new CombineIterator($keysInner, $valuesInner);

        $result = iterator_to_array($iterator);

        $this->assertSame($assoc, $result);
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

    public function testNestedIteratorAggregate()
    {
        $keys = new class() implements \IteratorAggregate {
            public function getIterator()
            {
                return new \ArrayObject(['I', 'II', 'III']);
            }
        };

        $values = new class() implements \IteratorAggregate {
            public function getIterator()
            {
                return new \ArrayObject(['uno', 'dos', 'tres']);
            }
        };

        $iterator = new CombineIterator($keys, $values);

        $result = iterator_to_array($iterator);

        $expected = ['I' => 'uno', 'II' => 'dos', 'III' => 'tres'];
        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new CombineIterator(new \EmptyIterator(), new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $keys = $this->createLazyExecutionIterator();
        $values = new \ArrayIterator(range(0, 10));

        new CombineIterator($keys, $values);

        $this->assertTrue(true, "No warning");
    }
}
