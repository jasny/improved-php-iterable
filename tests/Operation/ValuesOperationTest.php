<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Operation\ValuesOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\ValuesOperation
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class ValuesOperationTest extends TestCase
{
    public function testIterate()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $iterator = new ValuesOperation($assoc);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_values($assoc), $result);
    }

    public function testIterateIterator()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayIterator($assoc);

        $iterator = new ValuesOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_values($assoc), $result);
    }

    public function testIterateArrayObject()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayObject($assoc);

        $iterator = new ValuesOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_values($assoc), $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new ValuesOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}