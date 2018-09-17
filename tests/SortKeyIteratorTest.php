<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\SortKeyIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\SortKeyIterator
 */
class SortKeyIteratorTest extends TestCase
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
        $inner = new \ArrayIterator($values);

        $iterator = new SortKeyIterator($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_keys($result));
        $this->assertNotEquals($keys, array_keys($result));

        $this->assertNotSame($inner, $iterator->getInnerIterator());
        $this->assertInstanceOf(\ArrayIterator::class, $iterator->getInnerIterator());

        $this->assertEquals($values, iterator_to_array($inner), "Original iterator should not be changed");
    }

    public function testIterateKey()
    {
        $values = [
            'India' => 'one',
            'Zulu' => 'two',
            'Papa' => 'three',
            'Bravo' => 'four'
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new SortKeyIterator($inner);

        $result = iterator_to_array($iterator);

        $expected = [
            'Bravo' => 'four',
            'India' => 'one',
            'Papa' => 'three',
            'Zulu' => 'two'
        ];

        $this->assertEquals($expected, $result);
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
        $iterator = new SortKeyIterator($generator);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_keys($result));

        $this->assertNotSame($generator, $iterator->getInnerIterator());
        $this->assertInstanceOf(\ArrayIterator::class, $iterator->getInnerIterator());
    }

    public function testIterateCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $inner = new \ArrayIterator(array_fill_keys($this->sorted, null));

        $iterator = new SortKeyIterator($inner, $compare);

        $result = iterator_to_array($iterator);

        $expected = array_fill_keys($this->sorted, null);
        uksort($expected, $compare);

        $this->assertEquals($expected, $result);
    }
    
    public function testIterateEmpty()
    {
        $iterator = new SortKeyIterator(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
