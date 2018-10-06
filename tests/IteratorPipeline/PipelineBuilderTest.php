<?php

declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline;

use Improved\IteratorPipeline\PipelineBuilder;
use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\PipelineBuilder
 */
class PipelineBuilderTest extends TestCase
{
    use LazyExecutionIteratorTrait;

    public function test()
    {
        $builder = new PipelineBuilder();

        $blueprint = $builder
            ->unique()
            ->sort(\SORT_REGULAR)
            ->map(function($value, $key) {
                return "$key:$value";
            })
            ->values()
            ->limit(3);

        $result1 = $blueprint
            ->with(['two' => 'India', 'one' => 'Bravo', 'four' => 'Zulu', 'three' => 'Papa', 'not' => 'Bravo'])
            ->toArray();

        $this->assertEquals(['one:Bravo', 'two:India', 'three:Papa'], $result1);

        $result2 = $blueprint
            ->with([34, 15, 46, 1, 44, 1, 15, 92])
            ->toArray();

        $this->assertEquals(['3:1', '1:15', '0:34'], $result2);
    }

    public function testInvoke()
    {
        $unique = (new PipelineBuilder())->unique()->values();

        $result1 = $unique(['one', 'two', 'three', 'one', 'four']);
        $this->assertEquals(['one', 'two', 'three', 'four'], $result1);

        $result2 = $unique([34, 15, 46, 1, 44, 1, 15, 92]);
        $this->assertEquals([34, 15, 46, 1, 44, 92], $result2);
    }

    public function testSetKeys()
    {
        $generator = function($keys) {
            foreach ($keys as $index => $key) {
                yield $key => $index;
            }
        };

        $roman = (new PipelineBuilder())
            ->setKeys($generator(['I' => [], 'II' => null, 'III' => 'v', 'IV' => 'v', 'V' => 0]));

        $result = $roman(range(1, 10));
        $this->assertEquals(['I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5], $result);
    }

    public function testThenSelf()
    {
        $first = (new PipelineBuilder())->unique()->values();
        $second = (new PipelineBuilder())->map(function($value) {
            return ucwords($value);
        });

        $titles = $first->then($second);

        $result = $titles(['one foo', 'two foo', 'three foo', 'one foo', 'four foo']);

        $this->assertEquals(['One Foo', 'Two Foo', 'Three Foo', 'Four Foo'], $result);
    }

    public function testThenSelfLazy()
    {
        $iterable = $this->createLazyExecutionIterator();

        $first = (new PipelineBuilder())->apply(function() {});
        $second = (new PipelineBuilder())->apply(function() {});

        $first->then($second)->with($iterable)->values();
    }
}
