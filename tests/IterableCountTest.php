<?php

declare(strict_types=1);

namespace Ipl\Tests;

use PHPUnit\Framework\TestCase;
use function Ipl\iterable_count;

/**
 * @covers \Ipl\iterable_count
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
