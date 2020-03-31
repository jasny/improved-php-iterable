<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_apply;
use function Improved\iterable_walk;

/**
 * @covers \Improved\iterable_walk
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
            'array'         => [$sets[0], $sets[0]],
            'ArrayIterator' => [new \ArrayIterator($sets[1]), $sets[1]],
            'ArrayObject'   => [new \ArrayObject($sets[2]), $sets[2]],
            'yield'         => [$this->generateAssoc($sets[3]), $sets[3]]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $objects)
    {
        $iterator = iterable_apply($values, function ($value, $key) {
            $value->key = $key;
        });

        $this->assertObjectNotHasAttribute('key', $objects['foo']);

        iterable_walk($iterator);

        $this->assertEquals('foo', $objects['foo']->key);
        $this->assertEquals('bar', $objects['bar']->key);
        $this->assertEquals('qux', $objects['qux']->key);
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
