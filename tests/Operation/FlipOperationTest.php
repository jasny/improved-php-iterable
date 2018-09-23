<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Operation\FlipOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\FlipOperation
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class FlipOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];

        $iterator = new FlipOperation($values);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_flip($values), $result);
    }

    public function testIterateNotUnique()
    {
        $values = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three', 'foo' => 'four'];

        $iterator = new FlipOperation($values);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(array_values($values), $resultKeys);
        $this->assertSame(array_keys($values), $resultValues);
    }

    public function testIterateMixed()
    {
        $values = ['one' => null, 'two' => new \stdClass(), 'three' => ['hello', 'world'], 'four' => 5.2];

        $iterator = new FlipOperation($values);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(array_values($values), $resultKeys);
        $this->assertSame(array_keys($values), $resultValues);
    }

    public function testIterateIterator()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayIterator($values);

        $iterator = new FlipOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_flip($values), $result);
    }

    public function testIterateArrayObject()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayObject($values);

        $iterator = new FlipOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_flip($values), $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new FlipOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
