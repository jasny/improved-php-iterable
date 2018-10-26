<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Traits\FindingTrait
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

    public function testFindKey()
    {
        $pipeline = new Pipeline(['I' => 'one', 'II' => 'two', 'III' => 'three']);

        $result = $pipeline->findKey(function($value) {
            return $value[0] === 't';
        });

        $this->assertEquals('II', $result);
    }


    public function testHasAny()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result1 = $pipeline->hasAny(function($value) {
            return $value[0] === 't';
        });
        $this->assertTrue($result1);

        $result2 = $pipeline->hasAny(function($value) {
            return $value[0] === 'x';
        });
        $this->assertFalse($result2);
    }

    public function testHasAll()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result1 = $pipeline->hasAll(function($value) {
            return strlen($value) > 1;
        });
        $this->assertTrue($result1);

        $result2 = $pipeline->hasAll(function($value) {
            return $value[0] === 't';
        });
        $this->assertFalse($result2);
    }

    public function testHasNone()
    {
        $pipeline = new Pipeline(['one', 'two', 'three']);

        $result1 = $pipeline->hasNone(function($value) {
            return $value[0] === 't';
        });
        $this->assertFalse($result1);

        $result2 = $pipeline->hasNone(function($value) {
            return $value[0] === 'x';
        });
        $this->assertTrue($result2);
    }


    public function testMin()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->min();
        $this->assertEquals(-337, $result);
    }

    public function testMinCallback()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->min(function($a, $b) {
            return abs($a) <=> abs($b);
        });
        $this->assertEquals(-7, $result);
    }

    public function testMax()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->max();
        $this->assertEquals(122, $result);
    }

    public function testMaxCallback()
    {
        $pipeline = new Pipeline([99, 24, -7, -337, 122]);

        $result = $pipeline->max(function($a, $b) {
            return abs($a) <=> abs($b);
        });
        $this->assertEquals(-337, $result);
    }
}
