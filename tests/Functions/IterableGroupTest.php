<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_group;

/**
 * @covers \Improved\iterable_group
 */
class IterableGroupTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        $objects = [
            (object)['type' => 'one'],
            (object)['type' => 'two'],
            (object)['type' => 'one'],
            (object)['type' => 'three'],
            (object)['type' => 'one'],
            (object)['type' => 'two']
        ];

        return $this->provideIterables($objects);
    }

    /**
     * @dataProvider provider
     */
    public function test($iterable, $objects)
    {
        $iterator = iterable_group($iterable, function(\stdClass $object) {
            return $object->type;
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one' => [
                $objects[0],
                $objects[2],
                $objects[4]
            ],
            'two' => [
                $objects[1],
                $objects[5]
            ],
            'three' => [
                $objects[3]
            ]
        ];

        $this->assertSame($expected, $result);
    }

    public function testMixed()
    {
        $parents = [
            new \stdClass(),
            new \stdClass(),
            null
        ];

        $objects = [
            (object)['type' => $parents[0]],
            (object)['type' => $parents[1]],
            (object)['type' => $parents[0]],
            (object)['type' => null],
            (object)['type' => $parents[0]],
            (object)['type' => $parents[1]]
        ];

        $iterator = iterable_group($objects, function(\stdClass $object) {
            return $object->type;
        });

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $expectedValues = [
            [
                $objects[0],
                $objects[2],
                $objects[4]
            ],
            [
                $objects[1],
                $objects[5]
            ],
            [
                $objects[3]
            ]
        ];

        $this->assertSame($parents, $resultKeys);
        $this->assertSame($expectedValues, $resultValues);
    }

    public function testKey()
    {
        $values = ['alpha' => 'one', 'bat' => 'two', 'apple' => 'three', 'cat' => 'four', 'air' => 'five',
            'beast' => 'six'];

        $iterator = iterable_group($values, function($value, $key) {
            return substr($key, 0, 1);
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'a' => ['one', 'three', 'five'],
            'b' => ['two', 'six'],
            'c' => ['four']
        ];

        $this->assertSame($expected, $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_group(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_group($iterator, function() {});

        $this->assertTrue(true, "No warning");
    }
}
