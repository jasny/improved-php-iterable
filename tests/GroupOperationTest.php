<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\GroupOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\GroupOperation
 */
class GroupOperationTest extends TestCase
{
    public function testIterate()
    {
        $objects = [
            (object)['type' => 'one'],
            (object)['type' => 'two'],
            (object)['type' => 'one'],
            (object)['type' => 'three'],
            (object)['type' => 'one'],
            (object)['type' => 'two']
        ];
        $inner = new \ArrayIterator($objects);

        $iterator = new GroupOperation($inner, function(\stdClass $object) {
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

    public function testIterateMixed()
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
        $inner = new \ArrayIterator($objects);

        $iterator = new GroupOperation($inner, function(\stdClass $object) {
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

    public function testIterateKey()
    {
        $values = ['alpha' => 'one', 'bat' => 'two', 'apple' => 'three', 'cat' => 'four', 'air' => 'five',
            'beast' => 'six'];
        $inner = new \ArrayIterator($values);

        $iterator = new GroupOperation($inner, function($value, $key) {
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

    public function testIterateArrayObject()
    {
        $objects = [
            (object)['type' => 'one'],
            (object)['type' => 'two'],
            (object)['type' => 'one']
        ];
        $inner = new \ArrayObject($objects);

        $iterator = new GroupOperation($inner, function(\stdClass $object) {
            return $object->type;
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one' => [$objects[0], $objects[2]],
            'two' => [$objects[1]]
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $iterator = new GroupOperation(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
