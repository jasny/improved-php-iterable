<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_values;

/**
 * @covers \Jasny\iterable_values
 */
class IterableValuesTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $tests = $this->provideIterables($assoc, true);

        foreach ($tests as &$test) {
            $test[1] = array_values($test[1]);
        }

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function testIterate($assoc, $expected)
    {
        $iterator = iterable_values($assoc);
        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = iterable_values(new \EmptyIterator());
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
