<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\ValuesIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\ValuesIterator
 */
class ValuesIteratorTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco',
            'six' => 'seis'];
        $inner = new \ArrayIterator($values);

        $iterator = new ValuesIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals(array_values($values), $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new ValuesIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
