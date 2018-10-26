<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_values;

/**
 * @covers \Improved\iterable_values
 */
class IterableValuesTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $tests = $this->provideIterables($assoc, true);

        foreach ($tests as &$test) {
            $test[1] = array_values($test[1]);
        }

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function testIterate($assoc, $expected)
    {
        $iterator = iterable_values($assoc);
        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = iterable_values(new \EmptyIterator());
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_values($iterator);

        $this->assertTrue(true, "No warning");
    }
}
