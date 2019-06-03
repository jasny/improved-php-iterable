<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_after;

/**
 * @covers \Improved\iterable_after
 */
class IterableAfterTest extends TestCase
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
        $iterator = iterable_after($values, function($value) {
            return $value === -2;
        });

        $result = iterator_to_array($iterator);

        $expectedKeys = range(9, 20);
        $expectedValues = range(-1, 10);

        $this->assertSame(array_combine($expectedKeys, $expectedValues), $result);
    }

    /**
     * @dataProvider provider
     */
    public function testIncluding($values)
    {
        $iterator = iterable_after($values, function($value) {
            return $value === -2;
        }, true);

        $result = iterator_to_array($iterator);

        $expectedKeys = range(8, 20);
        $expectedValues = range(-2, 10);

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
        $iterator = iterable_after($values, function($value, $key) {
            return $key === 'berry';
        });

        $result = iterator_to_array($iterator);

        $this->assertSame(['cherry' => 'red', 'apricot' => 'orange'], $result);
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
        $iterator = iterable_after($generator, function($value, $key) {
            return $key === null;
        });

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([4, 5], $resultValues);
        $this->assertSame(array_slice($keys, 3), $resultKeys);
    }

    public function testEmpty()
    {
        $iterator = iterable_after(new \EmptyIterator(), function() {
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

        iterable_after($iterator, function() { return true; });

        $this->assertTrue(true, "No warning");
    }
}
