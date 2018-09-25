<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Tests\Traits;

use Jasny\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Traits\FilteringTrait
 */
class FilteringTraitTest extends TestCase
{
    public function testFilter()
    {
        $pipeline = new Pipeline(['apple', 'pear', 'berry', 'apricot', 'banana', 'cherry']);

        $ret = $pipeline->filter(function($value, $key) {
            return $key === 1 || $value[0] === 'a';
        });

        $this->assertSame($pipeline, $ret);

        $expected = [0 => 'apple', 1 => 'pear', 3 => 'apricot'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testCleanup()
    {
        $pipeline = new Pipeline(['apple', 'pear', null, 'apricot', null]);

        $ret = $pipeline->cleanup();

        $this->assertSame($pipeline, $ret);

        $expected = [0 => 'apple', 1 => 'pear', 3 => 'apricot'];
        $this->assertEquals($expected, $pipeline->toArray());
    }


    public function testUnique()
    {
        $pipeline = new Pipeline(['a', 'b', 0, null, 'b', 0, 42]);

        $ret = $pipeline->unique();

        $this->assertSame($pipeline, $ret);

        $expected = [0 => 'a', 1 => 'b', 2 => 0, 3 => null, 6 => 42];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testUniqueCallback()
    {
        $pipeline = new Pipeline(['apple', 'pear', 'berry', 'apricot', 'banana', 'cherry']);

        $ret = $pipeline->unique(function($value) {
            return $value[0];
        });

        $this->assertSame($pipeline, $ret);

        $expected = [0 => 'apple', 1 => 'pear', 2 => 'berry', 5 => 'cherry'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testUniqueKeys()
    {
        $generator = function ($keys) {
            foreach ($keys as $i => $key) {
                yield $key => $i;
            }
        };

        $iterable = $generator(['a', 'b', 0, 'b', 0, 42]);
        $pipeline = new Pipeline($iterable);

        $ret = $pipeline->uniqueKeys();

        $this->assertSame($pipeline, $ret);

        $expected = ['a' => 0, 'b' => 1, 0 => 2, 42 => 5];
        $this->assertEquals($expected, $pipeline->toArray());
    }


    public function testLimit()
    {
        $pipeline = new Pipeline(['apple', 'pear', 'berry', 'apricot', 'banana', 'cherry']);

        $ret = $pipeline->limit(3);
        $this->assertSame($pipeline, $ret);

        $this->assertEquals(['apple', 'pear', 'berry'], $pipeline->toArray());
    }

    public function testSlice()
    {
        $pipeline = new Pipeline(['apple', 'pear', 'berry', 'apricot', 'banana', 'cherry']);

        $ret = $pipeline->slice(2, 3);
        $this->assertSame($pipeline, $ret);

        $this->assertEquals([2 => 'berry', 3 => 'apricot', 4 => 'banana'], $pipeline->toArray());
    }


    public function testExpectType()
    {
        $pipeline = new Pipeline(['foo', 'bar']);

        $ret = $pipeline->expectType('string');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals(['foo', 'bar'], $result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected all elements to be of type string, integer given
     */
    public function testExpectTypeDefaultMessage()
    {
        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->expectType('string');
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage integer should be string
     */
    public function testExpectTypeCustomMessage()
    {
        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->expectType('string', '%s should be %s');
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }
}
