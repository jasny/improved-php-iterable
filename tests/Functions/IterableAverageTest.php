<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_average;

/**
 * @covers \Improved\iterable_average
 */
class IterableAverageTest extends TestCase
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
        $result = iterable_average($values);

        $this->assertEquals(63.75, $result);
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
        $result = iterable_average($values);

        $this->assertEquals(38.2, $result);
    }

    public function testAggregateEmpty()
    {
        $result = iterable_average(new \EmptyIterator());

        $this->assertNan($result);
    }
}
