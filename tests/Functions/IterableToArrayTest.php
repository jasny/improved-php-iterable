<?php

declare(strict_types=1);

namespace Ipl\Tests\Functions;

use Ipl\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Ipl\iterable_to_array;

/**
 * @covers \Ipl\iterable_to_array
 */
class IterableToArrayTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three'], true);
    }

    public function noTrickyProvider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three'], false);
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $result = iterable_to_array($values);

        if ($values instanceof \Generator) {
            $expected = array_values($expected);
        }

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider noTrickyProvider
     */
    public function testPreverseKeys($values, $expected)
    {
        $result = iterable_to_array($values, true);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider provider
     */
    public function testNoKeys($values, $expected)
    {
        $result = iterable_to_array($values, false);

        $this->assertSame(array_values($expected), $result);
    }

    public function testEmpty()
    {
        $result = iterable_to_array(new \EmptyIterator());

        $this->assertEquals([], $result);
    }
}
