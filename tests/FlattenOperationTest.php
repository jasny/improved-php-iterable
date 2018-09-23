<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\FlattenOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\TraversableIteratorTrait
 * @covers \Jasny\Iterator\FlattenOperation
 */
class FlattenOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = [
            ['one', 'two'],
            ['three', 'four', 'five'],
            [],
            'six',
            ['seven']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six', 'seven'];
        $this->assertEquals($expected, $result);
    }

    public function testIterateKey()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos'],
            ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            [],
            'six' => 'seis',
            ['seven' => 'sept']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenOperation($inner, true);

        $result = iterator_to_array($iterator);

        $expected = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco',
            'six' => 'seis', 'seven' => 'sept'];
        $this->assertEquals($expected, $result);
    }

    public function testIterateNoKey()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos'],
            ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            [],
            'six' => 'seis',
            ['seven' => 'sept']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenOperation($inner, false);

        $result = iterator_to_array($iterator);

        $expected = ['uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'sept'];
        $this->assertEquals($expected, $result);
    }

    public function testIterateIterators()
    {
        $values = [
            new \ArrayIterator(['one', 'two']),
            new \ArrayObject(['three', 'four', 'five']),
            new \EmptyIterator(),
            'six',
            ['seven']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six', 'seven'];

        $this->assertEquals($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new FlattenOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    public function testKeyNull()
    {
        $values = [
            ['one'],
            ['two']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenOperation($inner);

        foreach ($iterator as $value);

        $this->assertNull($iterator->key());
    }

    public function testIterateArrayObject()
    {
        $values = [
            ['one', 'two'],
            ['three', 'four', 'five'],
            [],
            'six',
            ['seven']
        ];
        $inner = new \ArrayObject($values);

        $iterator = new FlattenOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six', 'seven'];
        $this->assertEquals($expected, $result);
    }

    public function testGetInnerIterator()
    {
        $inner = new \EmptyIterator();
        $iterator = new FlattenOperation($inner);

        $this->assertSame($inner, $iterator->getInnerIterator());
    }
}
