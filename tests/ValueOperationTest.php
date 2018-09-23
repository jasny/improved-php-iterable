<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\ValueOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\ValueOperation
 */
class ValueOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayIterator($values);

        $iterator = new ValueOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_values($values), $result);
    }

    public function testIterateArrayObject()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayObject($values);

        $iterator = new ValueOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_values($values), $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new ValueOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
