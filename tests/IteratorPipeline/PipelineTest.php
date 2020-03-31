<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline;

use Improved as i;
use Improved\IteratorPipeline\Pipeline;
use Improved\IteratorPipeline\PipelineBuilder;
use Jasny\PHPUnit\CallbackMockTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Pipeline
 */
class PipelineTest extends TestCase
{
    use CallbackMockTrait;

    public function testThen()
    {
        $input = new \ArrayIterator(['a']);
        $next = new \ArrayIterator(['z']);

        $callback = $this->createCallbackMock($this->once(), [$this->identicalTo($input), 'foo', 42], $next);

        $pipeline = new Pipeline($input);

        $ret = $pipeline->then($callback, 'foo', 42);
        $this->assertSame($pipeline, $ret);

        $this->assertSame($next, $pipeline->getIterator());
    }

    public function testThenPipeline()
    {
        $mainpipe = new Pipeline([1, 2, 3]);
        $subpipe = new class ([9, 8, 7]) extends Pipeline { };

        $pipeline = $mainpipe->then(function () use ($subpipe) {
            return $subpipe;
        });

        // then() returns subpipe
        $this->assertSame($subpipe, $pipeline);

        // But also sets mainpipe step
        $result = i\iterable_to_array($mainpipe);
        $this->assertEquals([9, 8, 7], $result);
    }

    public function testThenUnexpectedValue()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Expected step to return an array or Traversable, instance of stdClass returned");

        $pipeline = new Pipeline(['a']);

        $pipeline->then(function () {
            return (object)[];
        });
    }


    public function provider()
    {
        $generator = function ($values) {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };

        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        $tests = [
            'array'         => [$values, $values],
            'ArrayIterator' => [new \ArrayIterator($values), $values],
            'ArrayObject'   => [new \ArrayObject($values), $values],
            'yield'         => [$generator($values), $values],
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
            ->apply(function ($value, $key) {
                $value->key = $key;
            })
            ->walk();

        $this->assertEquals('foo', $objects['foo']->key);
        $this->assertEquals('bar', $objects['bar']->key);
        $this->assertEquals('qux', $objects['qux']->key);
    }


    /**
     * @dataProvider provider
     */
    public function testWith($values, $expected)
    {
        $pipeline = Pipeline::with($values);
        $this->assertInstanceOf(Pipeline::class, $pipeline);

        $this->assertEquals($expected, iterator_to_array($pipeline, true));
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
