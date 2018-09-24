<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_reduce;

/**
 * @covers \Jasny\iterable_reduce
 */
class IterableReduceTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables([2, 3, 4], true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_reduce($values, function ($product, $value) {
            return $product * $value;
        }, 1);

        $this->assertEquals(24, $result);
    }

    public function testEmpty()
    {
        $result = iterable_reduce(new \EmptyIterator(), function () {
            return 10;
        }, 1);

        $this->assertEquals(1, $result);
    }
}
