<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\SortIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\SortIterator
 */
class SortIteratorTest extends TestCase
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

        $inner = new \ArrayIterator($values);

        $iterator = new SortIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_values($result));
        $this->assertNotEquals($values, array_values($result));

        $this->assertSame($inner, $iterator->getInnerIterator());
    }

    public function testSortedKey()
    {
        $values = [
            'one' => 'India',
            'two' => 'Zulu',
            'three' => 'Papa',
            'four' => 'Bravo'
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new SortIterator($inner);

        $result = iterator_to_array($iterator);

        $expected = [
            'four' => 'Bravo',
            'one' => 'India',
            'three' => 'Papa',
            'two' => 'Zulu'
        ];

        $this->assertEquals($expected, $result);
    }

    public function testSortGenerator()
    {
        $values = $this->sorted;
        shuffle($values);

        $loop = function($values) {
            foreach ($values as $value) {
                yield $value;
            }
        };

        $generator = $loop($values);
        $iterator = new SortIterator($generator);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_values($result));

        $this->assertNotSame($generator, $iterator->getInnerIterator());
        $this->assertInstanceOf(\ArrayIterator::class, $iterator->getInnerIterator());
    }

    public function testSortCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $inner = new \ArrayIterator($this->sorted);
        $inner->uasort($compare);

        $iterator = new SortIterator($inner, $compare);

        $result = iterator_to_array($iterator);

        $expected = $this->sorted;
        usort($expected, $compare);

        $this->assertEquals($expected, array_values($result));
    }
}
