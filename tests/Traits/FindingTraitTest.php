<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Tests\Traits;

use Jasny\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Traits\FindingTrait
 */
class FindingTraitTest extends TestCase
{
    public function testFirst()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result = $pipeline->first();
        $this->assertEquals('one', $result);
    }

    /**
     * @expectedException \RangeException
     * @expectedExceptionMessage Unable to get first element; iterable is empty
     */
    public function testFirstEmptyRequired()
    {
        $pipeline = new Pipeline([]);
        $pipeline->first(true);
    }

    public function testLast()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result = $pipeline->last();
        $this->assertEquals('three', $result);
    }

    /**
     * @expectedException \RangeException
     * @expectedExceptionMessage Unable to get last element; iterable is empty
     */
    public function testLastEmptyRequired()
    {
        $pipeline = new Pipeline([]);
        $pipeline->last(true);
    }


    public function testFind()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result = $pipeline->find(function($value) {
            return $value[0] === 't';
        });

        $this->assertEquals('two', $result);
    }


    public function testMin()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->min();
        return $this->assertEquals(-337, $result);
    }

    public function testMinCallback()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->min(function($a, $b) {
            return abs($a) <=> abs($b);
        });
        return $this->assertEquals(-7, $result);
    }

    public function testMax()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->max();
        return $this->assertEquals(122, $result);
    }

    public function testMaxCallback()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->max(function($a, $b) {
            return abs($a) <=> abs($b);
        });
        return $this->assertEquals(-337, $result);
    }
}
