<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_apply;

/**
 * @covers \Improved\iterable_apply
 */
class IterableApplyTest extends TestCase
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
            'arrayObject'   => [new \ArrayObject($sets[2]), $sets[2]],
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
            return 10; // Should be ignored
        });

        $this->assertObjectNotHasAttribute('key', $objects['foo']);

        $result = iterator_to_array($iterator);

        $this->assertSame($objects, $result);

        $this->assertEquals('foo', $objects['foo']->key);
        $this->assertEquals('bar', $objects['bar']->key);
        $this->assertEquals('qux', $objects['qux']->key);
    }

    public function testEmpty()
    {
        $iterator = iterable_apply(new \EmptyIterator(), function () {});
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();
        iterable_apply($iterator, function () {});
    }
}
