<?php

namespace Jasny\IteratorProjection\Tests;

use Jasny\IteratorProjection\Projection\SortKeyProjection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorProjection\Projection\SortKeyProjection
 */
class SortKeyProjectionTest extends TestCase
{
    protected $sorted = [
        "Alpha",
        "Bravo",
        "Charlie",
        "Delta",
        "Echo",
        "Foxtrot",
        "Golf",
        "Hotel",
        "India",
        "Juliet",
        "Kilo",
        "Lima",
        "Mike",
        "November",
        "Oscar",
        "Papa",
        "Quebec",
        "Romeo",
        "Sierra",
        "Tango",
        "Uniform",
        "Victor",
        "Whiskey",
        "X-ray",
        "Yankee",
        "Zulu"
    ];

    public function testIterate()
    {
        $keys = $this->sorted;
        shuffle($keys);

        $values = array_fill_keys($keys, null);

        $iterator = new SortKeyProjection($values);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, array_keys($result));
        $this->assertNotSame($keys, array_keys($result));
    }

    public function testIterateKey()
    {
        $values = [
            'India' => 'one',
            'Zulu' => 'two',
            'Papa' => 'three',
            'Bravo' => 'four'
        ];

        $iterator = new SortKeyProjection($values);

        $result = iterator_to_array($iterator);

        $expected = [
            'Bravo' => 'four',
            'India' => 'one',
            'Papa' => 'three',
            'Zulu' => 'two'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateGenerator()
    {
        $keys = $this->sorted;
        shuffle($keys);

        $loop = function($keys) {
            foreach ($keys as $key) {
                yield $key => null;
            }
        };

        $generator = $loop($keys);
        $iterator = new SortKeyProjection($generator);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, array_keys($result));
    }

    public function testIterateIterator()
    {
        $keys = $this->sorted;
        shuffle($keys);

        $values = array_fill_keys($keys, null);
        $inner = new \ArrayObject($values);

        $iterator = new SortKeyProjection($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, array_keys($result));
        $this->assertNotSame($keys, array_keys($result));

        $this->assertSame($values, iterator_to_array($inner), "Original iterator should not be changed");
    }

    public function testIterateArrayable()
    {
        $inner = \SplFixedArray::fromArray(array_fill(0, 10, null));
        $iterator = new SortKeyProjection($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame(range(0, 9), array_keys($result));
    }

    public function testIterateArrayObject()
    {
        $keys = $this->sorted;
        shuffle($keys);

        $values = array_fill_keys($keys, null);
        $inner = new \ArrayObject($values);

        $iterator = new SortKeyProjection($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, array_keys($result));
        $this->assertNotSame($keys, array_keys($result));
    }

    public function testIterateCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $inner = new \ArrayIterator(array_fill_keys($this->sorted, null));

        $iterator = new SortKeyProjection($inner, $compare);

        $result = iterator_to_array($iterator);

        $expected = array_fill_keys($this->sorted, null);
        uksort($expected, $compare);

        $this->assertSame($expected, $result);
    }
    
    public function testIterateEmpty()
    {
        $iterator = new SortKeyProjection(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }
}
