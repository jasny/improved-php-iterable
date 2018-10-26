<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_reverse;

/**
 * @covers \Improved\iterable_reverse
 */
class IterableReverseTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        $tests = $this->provideIterables(range(3, 12), true);

        foreach ($tests as &$test) {
            $test[1] = array_reverse($test[1]);
        }

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $iterator = iterable_reverse($values);
        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }


    public function preserveKeysProvider()
    {
        $tests = $this->provideIterables(range(3, 12));

        foreach ($tests as &$test) {
            $test[1] = array_reverse($test[1], true);
        }

        return $tests;
    }

    /**
     * @dataProvider preserveKeysProvider
     */
    public function testPreserveKeys($values, $expected)
    {
        $iterator = iterable_reverse($values, true);
        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    public function testGenerator()
    {
        $keys = [(object)['a' => 'a'], ['b' => 'b'], null, 'd', 'd'];

        $loop = function($keys) {
            foreach ($keys as $i => $key) {
                yield $key => $i + 1;
            }
        };

        $generator = $loop($keys);
        $iterator = iterable_reverse($generator, true);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertEquals([5, 4, 3, 2, 1], $resultValues);
        $this->assertEquals(array_reverse($keys, false), $resultKeys);
    }

    public function testEmpty()
    {
        $iterator = iterable_reverse(new \EmptyIterator());
        $result = iterator_to_array($iterator);
        
        $this->assertEquals([], $result);
    }

    public function testEmptyPreserveKeys()
    {
        $iterator = iterable_reverse(new \EmptyIterator(), true);
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_reverse($iterator);

        $this->assertTrue(true, "No warning");
    }
}
