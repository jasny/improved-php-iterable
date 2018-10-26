<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_filter;

/**
 * @covers \Improved\iterable_filter
 */
class IterableFilterTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        return $this->provideIterables(range(-10, 10));
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_filter($values, function($value) {
            return $value % 2 === 0;
        });

        $result = iterator_to_array($iterator);

        $expectedKeys = range(0, 20, 2);
        $expectedValues = range(-10, 10, 2);

        $this->assertSame(array_combine($expectedKeys, $expectedValues), $result);
    }

    public function keyProvider()
    {
        $values = ['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'];

        return $this->provideIterables($values, false, false);
    }

    /**
     * @dataProvider keyProvider
     */
    public function testKey($values)
    {
        $iterator = iterable_filter($values, function($value, $key) {
            return $key[0] === 'a';
        });

        $result = iterator_to_array($iterator);

        $this->assertSame(['apple' => 'green', 'apricot' => 'orange'], $result);
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
        $iterator = iterable_filter($generator, function($value, $key) {
            return !is_scalar($key);
        });

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([1, 2, 3], $resultValues);
        $this->assertSame(array_slice($keys, 0, 3), $resultKeys);
    }

    public function testEmpty()
    {
        $iterator = iterable_filter(new \EmptyIterator(), function() {
            return true;
        });

        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_filter($iterator, function() { return true; });

        $this->assertTrue(true, "No warning");
    }
}
