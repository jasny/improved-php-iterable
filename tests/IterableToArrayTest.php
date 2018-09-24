<?php

namespace Jasny\IteratorPipeline\Tests;

use function Jasny\iterable_to_array;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\iterable_to_array
 */
class IterableToArrayTest extends TestCase
{
    public function provider()
    {
        $generate = function($values): \Generator {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };

        $values = ['I' => 'one', 'II' => 'two', 'III' => 'three'];

        return [
            [$values],
            [new \ArrayIterator($values)],
            [new \ArrayObject($values)],
            \SplFixedArray::fromArray($values),
            $generate($values)
        ];
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_to_array($values);

        $this->assertEquals($values, $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testEmpty()
    {
        $result = iterable_to_array(new \EmptyIterator());

        $this->assertEquals([], $result);
    }
}
