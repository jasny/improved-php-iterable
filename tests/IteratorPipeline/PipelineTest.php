<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline;

use Improved as i;
use Improved\IteratorPipeline\Pipeline;
use Improved\IteratorPipeline\PipelineBuilder;
use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Pipeline
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

    public function testThenPipeline()
    {
        $mainpipe = new Pipeline([1, 2, 3]);
        $subpipe = new class([9, 8, 7]) extends Pipeline { };

        $pipeline = $mainpipe->then(function() use($subpipe) {
            return $subpipe;
        });

        // then() returns subpipe
        $this->assertSame($subpipe, $pipeline);

        // But also sets mainpipe step
        $result = i\iterable_to_array($mainpipe);
        $this->assertEquals([9, 8, 7], $result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected step to return an array or Traversable, instance of stdClass returned
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
