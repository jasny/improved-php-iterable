<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_reverse;

/**
 * @covers \Jasny\iterable_reverse
 */
class IterableReverseTest extends TestCase
{
    use ProvideIterablesTrait;

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
        $iterable = iterable_reverse($values);
        $result = is_array($iterable) ? $iterable : iterator_to_array($iterable);

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
        $iterable = iterable_reverse($values, true);
        $result = is_array($iterable) ? $iterable : iterator_to_array($iterable);

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
        $iterable = iterable_reverse($generator, true);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterable as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertEquals([5, 4, 3, 2, 1], $resultValues);
        $this->assertEquals(array_reverse($keys, false), $resultKeys);
    }

    public function testEmpty()
    {
        $result = iterable_reverse(new \EmptyIterator());
        $this->assertEquals([], $result);
    }

    public function testEmptyPreserveKeys()
    {
        $iterable = iterable_reverse(new \EmptyIterator(), true);
        $result = iterator_to_array($iterable);

        $this->assertEquals([], $result);
    }
}
