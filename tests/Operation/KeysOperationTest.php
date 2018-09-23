<?php

namespace Jasny\IteratorProjection\Tests;

use Jasny\IteratorProjection\Operation\KeysOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorProjection\Operation\KeysOperation
 * @covers \Jasny\IteratorProjection\Operation\AbstractOperation
 */
class KeysOperationTest extends TestCase
{
    public function testIterate()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $iterator = new KeysOperation($assoc);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($assoc), $result);
    }

    public function testIterateIterator()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayIterator($assoc);

        $iterator = new KeysOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($assoc), $result);
    }

    public function testIterateArrayOjbect()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayObject($assoc);

        $iterator = new KeysOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($assoc), $result);
    }
    public function testIterateEmpty()
    {
        $iterator = new KeysOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
