<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_last;

/**
 * @covers \Improved\iterable_last
 */
class IterableLastTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', 'three'], true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_last($values);

        $this->assertEquals('three', $result);
    }

    public function testEmpty()
    {
        $result = iterable_last(new \EmptyIterator());

        $this->assertNull($result);
    }

    /**
     * @expectedException \RangeException
     * @expectedExceptionMessage  Unable to get last element; iterable is empty
     */
    public function testEmptyRequired()
    {
        iterable_last(new \EmptyIterator(), true);
    }
}
