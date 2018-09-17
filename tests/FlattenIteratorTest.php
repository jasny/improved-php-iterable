<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\FlattenIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\FlattenIterator
 */
class FlattenIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = [
            ['one', 'two'],
            ['three', 'four', 'five'],
            [],
            ['six']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenIterator($inner);

        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six'];

        $this->assertEquals($expected, $result);
    }

    public function testIterateKey()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos'],
            ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            [],
            ['six' => 'seis']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenIterator($inner, true);

        $result = iterator_to_array($iterator);

        $expected = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco',
            'six' => 'seis'];

        $this->assertEquals($expected, $result);
    }

    public function testIterateIterators()
    {
        $values = [
            new \ArrayIterator(['one', 'two']),
            new \ArrayObject(['three', 'four', 'five']),
            new \EmptyIterator(),
            ['six']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenIterator($inner);

        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six'];

        $this->assertEquals($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new FlattenIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    public function testKeyNull()
    {
        $values = [
            ['one', 'two'],
            ['three', 'four', 'five']
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenIterator($inner);

        foreach ($iterator as $value);

        $this->assertNull($iterator->key());
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected an array, Iterator or IteratorAggregate, got string
     */
    public function testUnexpectedValue()
    {
        $values = ['foo-bar'];
        $inner = new \ArrayIterator($values);

        $iterator = new FlattenIterator($inner);

        iterator_to_array($iterator);
    }

    public function testGetInnerIterator()
    {
        $inner = new \EmptyIterator();
        $iterator = new FlattenIterator($inner);

        $this->assertSame($inner, $iterator->getInnerIterator());
    }
}
