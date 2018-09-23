<?php

namespace Jasny\IteratorProjection\Tests;

use Jasny\IteratorProjection\Operation\FlattenOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorProjection\Operation\FlattenOperation
 * @covers \Jasny\IteratorProjection\Operation\AbstractOperation
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

        $iterator = new FlattenOperation($values);

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

        $iterator = new FlattenOperation($values, true);

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

        $iterator = new FlattenOperation($values, false);

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

    public function testIterateEmpty()
    {
        $iterator = new FlattenOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
