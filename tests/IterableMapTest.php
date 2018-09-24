<?php

namespace Jasny\Tests;

use PHPUnit\Framework\TestCase;
use function Jasny\iterable_map;

/**
 * @covers \Jasny\iterable_map
 */
class IterableMapTest extends TestCase
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
        $iterator = iterable_map($values, function($value) {
            return str_repeat('*', $value);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            '*',
            '**',
            '***',
            '****'
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
        $iterator = iterable_map($values, function($value, $key) {
            return "$key-$value";
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one' => 'one-foo',
            'two' => 'two-bar',
            'three' => 'three-qux'
        ];

        $this->assertSame($expected, $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_map(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
