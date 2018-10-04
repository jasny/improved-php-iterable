<?php

declare(strict_types=1);

namespace Ipl\Tests\IteratorPipeline;

use Ipl\IteratorPipeline\Pipeline;
use Ipl\IteratorPipeline\PipelineBuilder;
use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ipl\IteratorPipeline\Pipeline
 */
class PipelineTest extends TestCase
{
    use TestHelper;


    public function testThen()
    {
        $input = new \ArrayIterator(['a']);
        $next = new \ArrayIterator(['z']);

        $callback = $this->createCallbackMock($this->once(), [$this->identicalTo($input), 'foo', 42], $next);

        $pipeline = new Pipeline($input);

        $ret = $pipeline->then($callback, 'foo', 42);
        $this->assertSame($pipeline, $ret);

        $this->assertAttributeSame($next, 'iterable', $pipeline);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected an array or Traversable, stdClass object returned
     */
    public function testThenUnexpectedValue()
    {
        $pipeline = new Pipeline(['a']);

        $pipeline->then(function() {
            return (object)[];
        });
    }


    public function provider()
    {
        $generator = function($values) {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };

        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        $tests = [
            [$values, $values],
            [new \ArrayIterator($values), $values],
            [new \ArrayObject($values), $values],
            [$generator($values), $values]
        ];

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function testToArray($values, $expected)
    {
        $pipeline = new Pipeline($values);
        $array = $pipeline->toArray();

        $this->assertEquals($expected, $array);
    }

    /**
     * @dataProvider provider
     */
    public function testGetIterator($values, $expected)
    {
        $pipeline = new Pipeline($values);
        $this->assertInstanceOf(\Traversable::class, $pipeline);

        $iterator = $pipeline->getIterator();

        $this->assertInstanceOf(\Iterator::class, $iterator);
        $this->assertEquals($expected, iterator_to_array($iterator, true));
    }

    public function testWalk()
    {
        $objects = ['foo' => new \stdClass(), 'bar' => new \stdClass(), 'qux' => new \stdClass()];

        $pipeline = new Pipeline($objects);
        $pipeline
            ->apply(function($value, $key) {
                $value->key = $key;
            })
            ->walk();

        $this->assertAttributeEquals('foo', 'key', $objects['foo']);
        $this->assertAttributeEquals('bar', 'key', $objects['bar']);
        $this->assertAttributeEquals('qux', 'key', $objects['qux']);
    }


    /**
     * @dataProvider provider
     */
    public function testWith($values)
    {
        $pipeline = Pipeline::with($values);
        $this->assertInstanceOf(Pipeline::class, $pipeline);

        $this->assertAttributeSame($values, 'iterable', $pipeline);
    }

    public function testWithSelf()
    {
        $input = new Pipeline([]);
        $pipeline = Pipeline::with($input);

        $this->assertInstanceOf(Pipeline::class, $pipeline);
        $this->assertSame($input, $pipeline);
    }

    public function testBuild()
    {
        $builder = Pipeline::build();
        $this->assertInstanceOf(PipelineBuilder::class, $builder);
    }
}
