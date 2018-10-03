<?php

declare(strict_types=1);

namespace Ipl\Tests\Functions;

use Ipl\Tests\LazyExecutionIteratorTrait;
use Ipl\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Ipl\iterable_apply;
use function Ipl\iterable_walk;

/**
 * @covers \Ipl\iterable_walk
 */
class IterableWalkTest extends TestCase
{
    use LazyExecutionIteratorTrait;
    use ProvideIterablesTrait;

    public function provider()
    {
        $sets = [];

        for ($i = 0; $i < 4; $i++) {
            $sets[$i] = ['foo' => new \stdClass(), 'bar' => new \stdClass(), 'qux' => new \stdClass()];
        }

        return [
            [$sets[0], $sets[0]],
            [new \ArrayIterator($sets[1]), $sets[1]],
            [new \ArrayObject($sets[2]), $sets[2]],
            [$this->generateAssoc($sets[3]), $sets[3]]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $objects)
    {
        $iterator = iterable_apply($values, function($value, $key) {
            $value->key = $key;
            return 10; // Should be ignored
        });

        $this->assertObjectNotHasAttribute('key', $objects['foo']);

        iterable_walk($iterator);

        $this->assertAttributeEquals('foo', 'key', $objects['foo']);
        $this->assertAttributeEquals('bar', 'key', $objects['bar']);
        $this->assertAttributeEquals('qux', 'key', $objects['qux']);
    }

    /**
     * @dataProvider provider
     */
    public function testNop($values)
    {
        iterable_walk($values);

        $this->assertTrue(true, 'No warning');
    }
}