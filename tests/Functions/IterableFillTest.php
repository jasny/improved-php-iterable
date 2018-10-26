<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_fill;

/**
 * @covers \Improved\iterable_fill
 */
class IterableFillTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        return $this->provideIterables(['I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4], false, false);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_fill($values, 42);

        $result = iterator_to_array($iterator);

        $this->assertSame(['I' => 42, 'II' => 42, 'III' => 42, 'IV' => 42], $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_fill(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_fill($iterator, function() {});

        $this->assertTrue(true, "No warning");
    }
}
