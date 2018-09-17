<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\KeysIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\KeysIterator
 */
class KeysIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco',
            'six' => 'seis'];
        $inner = new \ArrayIterator($values);

        $iterator = new KeysIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_keys($values), $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new KeysIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
