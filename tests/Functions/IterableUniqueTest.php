<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_unique;

/**
 * @covers \Improved\iterable_unique
 */
class IterableUniqueTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        return $this->provideIterables(['foo', 'bar', 'qux', 'foo', 'zoo', 'foo', 'bar']);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_unique($values);
        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo', 1 => 'bar', 2 => 'qux', 4 => 'zoo'], $result);
    }

    public function testObjects()
    {
        $first = (object)[];
        $second = (object)[];
        $third = (object)[];

        $values = [$first, $second, $first, $third, $first, $second];

        $iterator = iterable_unique($values);
        $result = iterator_to_array($iterator);

        $this->assertSame([0 => $first, 1 => $second, 3 => $third], $result);
    }

    public function testCallback()
    {
        $values = ['foo53', 'bar76', 'qux24', 'foo99', 'zoo34', 'foo22', 'bar11'];

        $iterator = iterable_unique($values, function($value) {
            return substr($value, 0, 3);
        });

        $result = iterator_to_array($iterator);

        $this->assertEquals([0 => 'foo53', 1 => 'bar76', 2 => 'qux24', 4 => 'zoo34'], $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_unique(new \EmptyIterator());
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_unique($iterator);

        $this->assertTrue(true, "No warning");
    }
}
