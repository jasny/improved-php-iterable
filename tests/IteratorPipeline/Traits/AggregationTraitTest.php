<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Traits\AggregationTrait
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

        $this->assertSame(42, $result);
    }

    public function testReduceWithKey()
    {
        $pipeline = new Pipeline(['I' => 'one', 'II' => 'two', 'III' => 'three']);

        $result = $pipeline->reduce(function ($list, $value, $key) {
            return $list . sprintf('{%s:%s}', $key, $value);
        }, '');

        $this->assertEquals('{I:one}{II:two}{III:three}', $result);
    }

    public function testSum()
    {
        $pipeline = new Pipeline([2, 3, 7]);

        $result = $pipeline->sum();
        $this->assertSame(12, $result);
    }

    public function testAverage()
    {
        $pipeline = new Pipeline([2, 3, 7]);

        $result = $pipeline->average();
        $this->assertSame(4.0, $result);
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
