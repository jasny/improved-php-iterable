<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_before;

/**
 * @covers \Improved\iterable_before
 */
class IterableBeforeTest extends TestCase
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
        $iterator = iterable_before($values, function($value) {
            return $value === 2;
        });

        $result = iterator_to_array($iterator);

        $expectedKeys = range(0, 11);
        $expectedValues = range(-10, 1);

        $this->assertSame(array_combine($expectedKeys, $expectedValues), $result);
    }

    /**
     * @dataProvider provider
     */
    public function testIncluding($values)
    {
        $iterator = iterable_before($values, function($value) {
            return $value === 2;
        }, true);

        $result = iterator_to_array($iterator);

        $expectedKeys = range(0, 12);
        $expectedValues = range(-10, 2);

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
        $iterator = iterable_before($values, function($value, $key) {
            return $key === 'cherry';
        });

        $result = iterator_to_array($iterator);

        $this->assertSame(['apple' => 'green', 'berry' => 'blue'], $result);
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
        $iterator = iterable_before($generator, function($value, $key) {
            return $key === null;
        });

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([1, 2], $resultValues);
        $this->assertSame(array_slice($keys, 0, 2), $resultKeys);
    }

    public function testEmpty()
    {
        $iterator = iterable_before(new \EmptyIterator(), function() {
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

        iterable_before($iterator, function() { return true; });

        $this->assertTrue(true, "No warning");
    }
}
