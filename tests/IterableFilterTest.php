<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_filter;

/**
 * @covers \Jasny\iterable_filter
 */
class IterableFilterTest extends TestCase
{
    public function testIterate()
    {
        $values = range(-10, 10);

        $iterator = iterable_filter($values, function($value) {
            return $value % 2 === 0;
        });

        $result = iterator_to_array($iterator);

        $expectedKeys = range(0, 20, 2);
        $expectedValues = range(-10, 10, 2);

        $this->assertSame(array_combine($expectedKeys, $expectedValues), $result);
    }

    public function testIterateKey()
    {
        $values = ['apple' => 'green', 'berry' => 'blue', 'cherry' => 'red', 'apricot' => 'orange'];

        $iterator = iterable_filter($values, function($value, $key) {
            return $key[0] === 'a';
        });

        $result = iterator_to_array($iterator);

        $this->assertSame(['apple' => 'green', 'apricot' => 'orange'], $result);
    }

    public function testIterateGenerator()
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

    public function testIterateEmpty()
    {
        $iterator = iterable_filter(new \EmptyIterator(), function($value, $key) {
            return true;
        });

        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }
}
