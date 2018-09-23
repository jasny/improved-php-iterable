<?php

namespace Jasny\IteratorProjection\Tests;

use Jasny\IteratorProjection\Projection\SortProjection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorProjection\Projection\SortProjection
 */
class SortProjectionTest extends TestCase
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
        $values = $this->sorted;
        shuffle($values);

        $iterator = new SortProjection($values);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, $result);
        $this->assertNotSame($values, $result);
    }

    public function testIterateSortFlags()
    {
        $values = [
            'img1.png',
            'img10.png',
            'img12.png',
            'img2.png'
        ];

        $iterator = new SortProjection($values, \SORT_NATURAL);

        $result = iterator_to_array($iterator);

        $expected = [
            'img1.png',
            'img2.png',
            'img10.png',
            'img12.png'
        ];
        $this->assertSame($expected, $result);
   }

    public function testIterateKey()
    {
        $values = [
            'one' => 'India',
            'two' => 'Zulu',
            'three' => 'Papa',
            'four' => 'Bravo'
        ];

        $iterator = new SortProjection($values, \SORT_REGULAR, true);

        $result = iterator_to_array($iterator);

        $expected = [
            'four' => 'Bravo',
            'one' => 'India',
            'three' => 'Papa',
            'two' => 'Zulu'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateGenerator()
    {
        $values = $this->sorted;
        shuffle($values);

        $loop = function($values) {
            foreach ($values as $value) {
                yield $value;
            }
        };

        $generator = $loop($values);
        $iterator = new SortProjection($generator);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, $result);
    }

    public function testIterateIterator()
    {
        $values = $this->sorted;
        shuffle($values);

        $inner = new \ArrayIterator($values);

        $iterator = new SortProjection($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, $result);

        $this->assertNotSame($values, $result);
        $this->assertSame($values, iterator_to_array($inner), "Original iterator should not be changed");
    }

    public function testIterateArrayable()
    {
        $values = $this->sorted;
        shuffle($values);

        $inner = \SplFixedArray::fromArray($values);
        $iterator = new SortProjection($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, $result);
    }

    public function testIterateArrayObject()
    {
        $values = $this->sorted;
        shuffle($values);

        $inner = new \ArrayObject($values);

        $iterator = new SortProjection($inner);

        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, $result);
    }

    public function testIterateCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $inner = new \ArrayIterator($this->sorted);

        $iterator = new SortProjection($inner, $compare);

        $result = iterator_to_array($iterator);

        $expected = $this->sorted;
        usort($expected, $compare);

        $this->assertSame($expected, $result);
    }

    public function testIterateCallbackKey()
    {
        $values = [
            'one' => 'India',
            'two' => 'Zulu',
            'three' => 'Papa',
            'four' => 'Bravo'
        ];

        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $iterator = new SortProjection($values, $compare, true);

        $result = iterator_to_array($iterator);

        $expected = [
            'three' => 'Papa',
            'two' => 'Zulu',
            'four' => 'Bravo',
            'one' => 'India'
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new SortProjection(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }
}
