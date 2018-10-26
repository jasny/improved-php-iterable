<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline;

use Improved as i;
use Improved\IteratorPipeline\Pipeline;
use Improved\IteratorPipeline\PipelineBuilder;
use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\PipelineBuilder
 * @covers \Improved\IteratorPipeline\PipelineBuilder\Stub
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

        $pipeline1 = $blueprint
            ->with(['two' => 'India', 'one' => 'Bravo', 'four' => 'Zulu', 'three' => 'Papa', 'not' => 'Bravo']);
        $this->assertInstanceOf(Pipeline::class, $pipeline1);

        $result1 = $pipeline1->toArray();
        $this->assertEquals(['one:Bravo', 'two:India', 'three:Papa'], $result1);

        $pipeline2 = $blueprint->with([34, 15, 46, 1, 44, 1, 15, 92]);
        $this->assertInstanceOf(Pipeline::class, $pipeline1);

        $result2 = $pipeline2->toArray();
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

    public function testStub()
    {
        $blueprint = (new PipelineBuilder())
            ->unique()
            ->sort(\SORT_REGULAR)
            ->stub('process')
            ->values()
            ->limit(3);

        $result1 = $blueprint
            ->with(['two' => 'India', 'one' => 'Bravo', 'four' => 'Zulu', 'three' => 'Papa', 'not' => 'Bravo'])
            ->toArray();
        $this->assertEquals(['Bravo', 'India', 'Papa'], $result1);

        $build = $blueprint
            ->unstub('process', i\iterable_map, function($value, $key) {
                return "$key:$value";
            });
        $this->assertNotSame($blueprint, $build);

        $result2 = $build
            ->with(['two' => 'India', 'one' => 'Bravo', 'four' => 'Zulu', 'three' => 'Papa', 'not' => 'Bravo'])
            ->toArray();

        $this->assertEquals(['one:Bravo', 'two:India', 'three:Papa'], $result2);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Pipeline builder already has 'process' stub
     */
    public function testStubDuplicate()
    {
        $blueprint = (new PipelineBuilder())
            ->sort(\SORT_REGULAR)
            ->stub('process')
            ->values();

        $blueprint->stub('process');
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Pipeline builder doesn't have 'process' stub
     */
    public function testStubUnknown()
    {
        $blueprint = (new PipelineBuilder())
            ->sort(\SORT_REGULAR)
            ->values();

        $blueprint->unstub('process', function() {});
    }

    public function testThenPipeline()
    {
        $subpipe = new class([9, 8, 7]) extends Pipeline { };

        $builder = (new PipelineBuilder())
            ->then(function() use($subpipe) {
                return $subpipe;
            });

        $pipeline = $builder->with([1, 2, 3]);

        // then() returns subpipe
        $this->assertSame($subpipe, $pipeline);
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
