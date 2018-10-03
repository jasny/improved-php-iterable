<?php

declare(strict_types=1);

namespace Ipl\Tests\Functions;

use Ipl\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Ipl\iterable_reduce;

/**
 * @covers \Ipl\iterable_reduce
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
