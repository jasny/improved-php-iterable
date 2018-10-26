<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_cleanup;

/**
 * @covers \Improved\iterable_cleanup
 */
class IterableCleanupTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', null, 'foo', 0, '', null, [], -100]);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_cleanup($values);
        $result = iterator_to_array($iterator);

        $expected = [0 => 'one', 1 => 'two', 3 => 'foo', 4 => 0, 5 => '', 7 => [], 8 => -100];
        $this->assertEquals($expected, $result);
    }

    public function testIterateGenerator()
    {
        $keys = [(object)['a' => 'a'], ['b' => 'b'], null, 'd', 'd'];
        $values = [1, 2, 3, 4, null];

        $loop = function($keys, $values) {
            foreach ($keys as $i => $key) {
                yield $key => $values[$i];
            }
        };

        $generator = $loop($keys, $values);
        $iterator = iterable_cleanup($generator);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([1, 2, 4], $resultValues);
        $this->assertSame([$keys[0], $keys[1], $keys[3]], $resultKeys);
    }

    public function testIterateEmpty()
    {
        $iterator = iterable_cleanup(new \EmptyIterator());
        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_cleanup($iterator);

        $this->assertTrue(true, "No warning");
    }
}
