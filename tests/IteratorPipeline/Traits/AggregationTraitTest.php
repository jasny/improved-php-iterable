<?php

declare(strict_types=1);

namespace Ipl\Tests\IteratorPipeline\Traits;

use Ipl\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ipl\IteratorPipeline\Traits\AggregationTrait
 */
class AggregationTraitTest extends TestCase
{
    public function testCount()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result = $pipeline->count();
        $this->assertSame(3, $result);
    }

    public function testReduce()
    {
        $pipeline = new Pipeline([2, 3, 7]);

        $result = $pipeline->reduce(function($product, $value) {
            return $product * $value;
        }, 1);

        return $this->assertSame(42, $result);
    }

    public function testSum()
    {
        $pipeline = new Pipeline([2, 3, 7]);

        $result = $pipeline->sum();
        return $this->assertSame(12, $result);
    }

    public function testAverage()
    {
        $pipeline = new Pipeline([2, 3, 7]);

        $result = $pipeline->average();
        return $this->assertSame(4.0, $result);
    }


    public function testConcat()
    {
        $pipeline = new Pipeline(['a', 'b', 'c']);

        $result = $pipeline->concat();
        $this->assertSame('abc', $result);
    }

    public function testConcatGlue()
    {
        $pipeline = new Pipeline(['a', 'b', 'c']);

        $result = $pipeline->concat(' - ');
        $this->assertSame('a - b - c', $result);
    }
}
