<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_min;

/**
 * @covers \Improved\iterable_min
 */
class IterableMinTest extends TestCase
{
    use ProvideIterablesTrait;

    public function intProvider()
    {
        return $this->provideIterables([99, 24, 122], true);
    }

    /**
     * @dataProvider intProvider
     */
    public function testInt($values)
    {
        $result = iterable_min($values);
        $this->assertEquals(24, $result);
    }

    
    public function negativeProvider()
    {
        return $this->provideIterables([99, 24, -7, -337, 122], true);
    }

    /**
     * @dataProvider negativeProvider
     */
    public function testNegative($values)
    {
        $result = iterable_min($values);
        $this->assertEquals(-337, $result);
    }


    public function floatProvider()
    {
        return $this->provideIterables([9.9, 99.1, 7.5, 8.0], true);
    }

    /**
     * @dataProvider floatProvider
     */
    public function testFloat()
    {
        $values = [9.9, 99.1, 7.5, 8.0];
        $iterator = new \ArrayIterator($values);

        $result = iterable_min($iterator);
        $this->assertEquals(7.5, $result);
    }

    public function stringProvider()
    {
        return $this->provideIterables(["Charlie", "Bravo", "Alpha", "Foxtrot", "Delta"], true);
    }

    /**
     * @dataProvider stringProvider
     */
    public function testString($values)
    {
        $result = iterable_min($values);
        $this->assertEquals("Alpha", $result);
    }

    /**
     * @dataProvider negativeProvider
     */
    public function testAbs($values)
    {
        $result = iterable_min($values, function($a, $b) {
            return abs($a) <=> abs($b);
        });

        $this->assertSame(-7, $result);
    }

    public function testCallback()
    {
        $values = [
            (object)['num' => 1, 'name' => "Charlie"],
            (object)['num' => 2, 'name' => "Bravo"],
            (object)['num' => 3, 'name' => "Alpha"],
            (object)['num' => 4, 'name' => "Foxtrot"],
            (object)['num' => 5, 'name' => "Delta"],
            (object)['num' => 6, 'name' => "Alpha"]
        ];
        $iterator = new \ArrayIterator($values);

        $result = iterable_min($iterator, function(\stdClass $a, \stdClass $b) {
            return $a->name <=> $b->name;
        });

        $this->assertSame($values[2], $result);
    }

    public function testEmpty()
    {
        $result = iterable_min(new \EmptyIterator());
        $this->assertNull($result);
    }
}
