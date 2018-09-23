<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\SortOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\ArrayIteratorAggregateTrait
 * @covers \Jasny\Iterator\SortOperation
 */
class SortOperationTest extends TestCase
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

        $iterator = new SortOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_values($result));

        $this->assertNotEquals($values, array_values($result));
        $this->assertEquals($values, iterator_to_array($inner), "Original iterator should not be changed");
    }

    public function testIterateKey()
    {
        $values = [
            'one' => 'India',
            'two' => 'Zulu',
            'three' => 'Papa',
            'four' => 'Bravo'
        ];
        $inner = new \ArrayIterator($values);

        $iterator = new SortOperation($inner);

        $result = iterator_to_array($iterator);

        $expected = [
            'four' => 'Bravo',
            'one' => 'India',
            'three' => 'Papa',
            'two' => 'Zulu'
        ];

        $this->assertEquals($expected, $result);
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
        $iterator = new SortOperation($generator);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_values($result));
    }

    public function testIterateArrayable()
    {
        $values = $this->sorted;
        shuffle($values);

        $inner = \SplFixedArray::fromArray($values);
        $iterator = new SortOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_values($result));
    }

    public function testIterateArrayObject()
    {
        $values = $this->sorted;
        shuffle($values);

        $inner = new \ArrayObject($values);

        $iterator = new SortOperation($inner);

        $result = iterator_to_array($iterator);

        $this->assertEquals($this->sorted, array_values($result));
    }

    public function testIterateCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $inner = new \ArrayIterator($this->sorted);

        $iterator = new SortOperation($inner, $compare);

        $result = iterator_to_array($iterator);

        $expected = $this->sorted;
        usort($expected, $compare);

        $this->assertEquals($expected, array_values($result));
    }
    
    public function testIterateEmpty()
    {
        $iterator = new SortOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
