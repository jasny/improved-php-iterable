<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_map_keys;

/**
 * @covers \Jasny\iterable_map_keys
 */
class IterableMapKeysTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(range(1, 4));
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_map_keys($values, function($key) {
            return str_repeat('*', $key);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            '' => 1,
            '*' => 2,
            '**' => 3,
            '***' => 4
        ];

        $this->assertSame($expected, $result);
    }

    public function keyValueProvider()
    {
        return $this->provideIterables(['one' => 'foo', 'two' => 'bar', 'three' => 'qux'], false, false);
    }

    /**
     * @dataProvider keyValueProvider
     */
    public function testKeyValue($values)
    {
        $iterator = iterable_map_keys($values, function($key, $value) {
            return "$key-$value";
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one-foo' => 'foo',
            'two-bar' => 'bar',
            'three-qux' => 'qux'
        ];

        $this->assertSame($expected, $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_map_keys(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
