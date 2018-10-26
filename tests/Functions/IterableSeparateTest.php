<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_separate;

/**
 * @covers \Improved\iterable_separate
 */
class IterableSeparateTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        $tests = $this->provideIterables(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        foreach ($tests as &$test) {
            $test[1] = ['keys' => array_keys($test[1]), 'values' => array_values($test[1])];
        }

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $result = iterable_separate($values);
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
        $result = iterable_separate($generator);

        $this->assertSame(compact('keys', 'values'), $result);
    }

    public function testIterateEmpty()
    {
        $result = iterable_separate(new \EmptyIterator());
        $this->assertSame(['keys' => [], 'values' => []], $result);
    }
}
