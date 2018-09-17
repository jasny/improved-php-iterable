<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\KeyIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\KeyIterator
 */
class KeyIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco',
            'six' => 'seis'];
        $inner = new \ArrayIterator($values);

        $iterator = new KeyIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($values), $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new KeyIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
