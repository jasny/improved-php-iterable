<?php

declare(strict_types=1);

namespace Jasny\PipelineIterator\Tests;

use Jasny\IteratorPipeline\Pipeline;
use Jasny\IteratorPipeline\PipelineBuilder;
use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Pipeline
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

    /**
     * @dataProvider provider
     */
    public function testWith($values)
    {
        $pipeline = Pipeline::with($values);
        $this->assertInstanceOf(Pipeline::class, $pipeline);

        $this->assertAttributeSame($values, 'iterable', $pipeline);
    }

    public function testBuild()
    {
        $builder = Pipeline::build();
        $this->assertInstanceOf(PipelineBuilder::class, $builder);
    }
}
