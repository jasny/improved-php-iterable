<?php

declare(strict_types=1);

namespace Ipl\Tests;

use PHPUnit\Framework\TestCase;
use function Ipl\iterable_has_all;
use function Jasny\str_contains;

/**
 * @covers \Ipl\iterable_has_all
 */
class IterableHasAllTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', 'three'], true);
    }

    /**
     * @dataProvider provider
     */
    public function testTrue($values)
    {
        $result = iterable_has_all($values, function($value) {
            return strlen($value) > 1;
        });

        $this->assertTrue($result);
    }

    /**
     * @dataProvider provider
     */
    public function testFalse($values)
    {
        $result = iterable_has_all($values, function($value) {
            return str_contains($value, 'o');
        });

        $this->assertFalse($result);
    }

    public function keyProvider()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        return $this->provideIterables($values, false, false);
    }

    /**
     * @dataProvider keyProvider
     */
    public function testKey($values)
    {
        $result = iterable_has_all($values, function($value, $key) {
            return strlen($key) > 1;
        });

        $this->assertTrue($result);
    }

    public function testNoWalk()
    {
        $iterator = $this->createMock(\Iterator::class);
        $iterator->expects($this->any())->method('valid')->willReturn(true);
        $iterator->expects($this->exactly(2))->method('current')
            ->willReturnOnConsecutiveCalls('one', 'two');

        $result = iterable_has_all($iterator, function($value) {
            return $value !== 'two';
        });

        $this->assertFalse($result);
    }

    public function testEmpty()
    {
        $result = iterable_has_all(new \EmptyIterator(), function() {});

        $this->assertTrue($result);
    }
}