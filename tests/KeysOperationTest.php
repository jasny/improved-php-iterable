<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\KeysOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\KeysOperation
 */
class KeysOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayIterator($values);

        $iterator = new KeysOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($values), $result);
    }

    public function testIterateArrayOjbect()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayObject($values);

        $iterator = new KeysOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($values), $result);
    }
    public function testIterateEmpty()
    {
        $iterator = new KeysOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
