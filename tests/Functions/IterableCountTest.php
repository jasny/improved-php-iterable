<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_count;

/**
 * @covers \Improved\iterable_count
 */
class IterableCountTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(array_fill(2, 6, null), true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_count($values);

        $this->assertEquals(6, $result);
    }

    public function testCountable()
    {
        $countable = new class([]) extends \ArrayIterator {
            public function count() {
                return 42;
            }
        };

        $result = iterable_count($countable);

        $this->assertEquals(42, $result);
    }

    public function testEmpty()
    {
        $result = iterable_count(new \EmptyIterator());

        $this->assertEquals(0, $result);
    }
}
