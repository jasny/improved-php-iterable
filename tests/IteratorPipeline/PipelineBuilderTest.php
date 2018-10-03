<?php

declare(strict_types=1);

namespace Ipl\Tests\IteratorPipeline;

use Ipl\IteratorPipeline\PipelineBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ipl\IteratorPipeline\PipelineBuilder
 */
class PipelineBuilderTest extends TestCase
{
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
}
