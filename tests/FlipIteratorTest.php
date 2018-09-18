<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\FlipIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\FlipIterator
 */
class FlipIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $inner = new \ArrayIterator($values);

        $iterator = new FlipIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_flip($values), $result);
    }

    public function testIterateNotUnique()
    {
        $values = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three', 'foo' => 'four'];
        $inner = new \ArrayIterator($values);

        $iterator = new FlipIterator($inner);

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
        $inner = new \ArrayIterator($values);

        $iterator = new FlipIterator($inner);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(array_values($values), $resultKeys);
        $this->assertSame(array_keys($values), $resultValues);
    }

    public function testIterateEmpty()
    {
        $iterator = new FlipIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
