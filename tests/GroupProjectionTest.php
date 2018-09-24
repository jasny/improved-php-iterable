<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Projection\iterablegroup;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Projection\iterablegroup
 */
class GroupProjectionTest extends TestCase
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

        $iterator = new iterablegroup($objects, function(\stdClass $object) {
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

        $iterator = new iterablegroup($objects, function(\stdClass $object) {
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

        $iterator = new iterablegroup($values, function($value, $key) {
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

    public function testIterateIterator()
    {
        $objects = [
            (object)['type' => 'one'],
            (object)['type' => 'two'],
            (object)['type' => 'one']
        ];
        $inner = new \ArrayIterator($objects);

        $iterator = new iterablegroup($inner, function(\stdClass $object) {
            return $object->type;
        });

        $result = iterator_to_array($iterator);

        $expected = [
            'one' => [$objects[0], $objects[2]],
            'two' => [$objects[1]]
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

        $iterator = new iterablegroup($inner, function(\stdClass $object) {
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
        $iterator = new iterablegroup(new \EmptyIterator(), function() {});

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
