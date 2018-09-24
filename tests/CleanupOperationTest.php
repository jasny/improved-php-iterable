<?php

namespace Jasny\Iterator\Tests\Operation;

use Jasny\IteratorPipeline\Operation\CleanupOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\iterablecleanup
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class CleanupOperationTest extends TestCase
{
    public function testIterate()
    {
        $values = ['one', 'two', null, 'foo', 0, '', null, [], -100];

        $iterator = new CleanupOperation($values);

        $result = iterator_to_array($iterator);

        $expected = [0 => 'one', 1 => 'two', 3 => 'foo', 4 => 0, 5 => '', 7 => [], 8 => -100];
        $this->assertEquals($expected, $result);
    }

    public function testIterateGenerator()
    {
        $keys = [(object)['a' => 'a'], ['b' => 'b'], null, 'd', 'd'];
        $values = [1, 2, 3, 4, null];

        $loop = function($keys, $values) {
            foreach ($keys as $i => $key) {
                yield $key => $values[$i];
            }
        };

        $generator = $loop($keys, $values);
        $iterator = new CleanupOperation($generator);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([1, 2, 3, 4], $resultValues);
        $this->assertSame(array_slice($keys, 0, 4), $resultKeys);
    }

    public function testIterateEmpty()
    {
        $iterator = new CleanupOperation(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }
}
