<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_flip;

/**
 * @covers \Improved\iterable_flip
 */
class IterableFlipTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $tests = $this->provideIterables($values);

        foreach ($tests as &$test) {
            $test[1] = array_flip($test[1]);
        }

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $iterator = iterable_flip($values);
        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    public function testNotUnique()
    {
        $values = ['foo' => 'one', 'bar' => 'two', 'qux' => 'three', 'woo' => 'one'];

        $iterator = iterable_flip($values);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(array_values($values), $resultKeys);
        $this->assertSame(array_keys($values), $resultValues);
    }

    public function testMixed()
    {
        $values = ['one' => null, 'two' => new \stdClass(), 'three' => ['hello', 'world'], 'four' => 5.2];

        $iterator = iterable_flip($values);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(array_values($values), $resultKeys);
        $this->assertSame(array_keys($values), $resultValues);
    }

    public function testEmpty()
    {
        $iterator = iterable_flip(new \EmptyIterator());
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_flip($iterator);

        $this->assertTrue(true, "No warning");
    }
}
