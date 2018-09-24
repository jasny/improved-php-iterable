<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_to_array;

/**
 * @covers \Jasny\iterable_to_array
 */
class IterableToArrayTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three']);
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $result = iterable_to_array($values);

        $this->assertSame($expected, $result);
    }

    public function testEmpty()
    {
        $result = iterable_to_array(new \EmptyIterator());

        $this->assertEquals([], $result);
    }
}
