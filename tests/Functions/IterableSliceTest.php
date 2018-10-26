<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_slice;

/**
 * @covers \Improved\iterable_slice
 */
class IterableSliceTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', 'three', 'four', 'five', 'six']);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_slice($values, 2, 3);
        $result = iterator_to_array($iterator, true);

        $this->assertEquals([2 => 'three', 3 => 'four', 4 => 'five'], $result);
    }

    /**
     * @dataProvider provider
     */
    public function testLimit($values)
    {
        $iterator = iterable_slice($values, 0, 3);
        $result = iterator_to_array($iterator, true);

        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    /**
     * @dataProvider provider
     */
    public function testOffset($values)
    {
        $iterator = iterable_slice($values, 2);
        $result = iterator_to_array($iterator, true);

        $this->assertEquals([2 => 'three', 3 => 'four', 4 => 'five', 5 => 'six'], $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_slice(new \EmptyIterator(), 0, 1);
        $result = iterator_to_array($iterator, true);

        $this->assertEquals([], $result);
    }
}
