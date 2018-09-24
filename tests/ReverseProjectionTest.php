<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Projection\iterablereverse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Projection\iterablereverse
 */
class ReverseProjectionTest extends TestCase
{
    public function testIterate()
    {
        $values = range(3, 12);

        $iterator = new iterablereverse($values);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values);
        $this->assertEquals($expected, $result);
    }

    public function testIteratePreserveKeys()
    {
        $values = range(3, 12);

        $iterator = new iterablereverse($values, true);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);
    }

    public function testIterateIterator()
    {
        $values = range(3, 12);
        $inner = new \ArrayIterator($values);

        $iterator = new iterablereverse($inner, true);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);

        $this->assertEquals($values, iterator_to_array($inner), "Original iterator should not be changed");
    }

    public function testIterateGenerator()
    {
        $keys = [(object)['a' => 'a'], ['b' => 'b'], null, 'd', 'd'];

        $loop = function($keys) {
            foreach ($keys as $i => $key) {
                yield $key => $i + 1;
            }
        };

        $generator = $loop($keys);
        $iterator = new iterablereverse($generator, true);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertEquals([5, 4, 3, 2, 1], $resultValues);
        $this->assertEquals(array_reverse($keys, false), $resultKeys);
    }

    public function testIterateArrayable()
    {
        $values = range(3, 12);

        $inner = \SplFixedArray::fromArray($values);
        $iterator = new iterablereverse($inner, true);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values, true);
        $this->assertEquals($expected, $result);
    }

    public function testIterateArrayObject()
    {
        $values = range(3, 12);

        $inner = new \ArrayObject($values);
        $iterator = new iterablereverse($inner);

        $result = iterator_to_array($iterator);

        $expected = array_reverse($values);
        $this->assertEquals($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new iterablereverse(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
