<?php

declare(strict_types=1);

namespace Ipl\Tests\Functions;

use Ipl\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Ipl\iterable_sum;

/**
 * @covers \Ipl\iterable_sum
 */
class IterableSumTest extends TestCase
{
    use ProvideIterablesTrait;

    public function intProvider()
    {
        return $this->provideIterables([10, 99, 24, 122], true);
    }

    /**
     * @dataProvider intProvider
     */
    public function testAggregateInt($values)
    {
        $result = iterable_sum($values);

        $this->assertEquals(255, $result);
    }

    public function floatProvider()
    {
        return $this->provideIterables([7.5, 99.1, 8], true);
    }

    /**
     * @dataProvider floatProvider
     */
    public function testAggregateFloat($values)
    {
        $result = iterable_sum($values);

        $this->assertEquals(114.6, $result);
    }

    public function testAggregateEmpty()
    {
        $result = iterable_sum(new \EmptyIterator());

        $this->assertEquals(0, $result);
    }
}