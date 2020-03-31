<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Traits\TypeHandlingTrait
 */
class TypeHandlingTraitTest extends TestCase
{
    public function testTypeCheck()
    {
        $pipeline = new Pipeline(['foo', 'bar']);

        $ret = $pipeline->typeCheck('string');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals(['foo', 'bar'], $result);
    }

    public function testTypeCheckDefaultMessage()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("Expected string, int(20) given; index int(1)");

        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->typeCheck('string');
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }

    public function testTypeCheckCustomError()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("int(20) should be string for index int(1)");

        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->typeCheck('string', new \InvalidArgumentException('%s should be %3$s for index %2$s'));
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }


    public function testTypeCast()
    {
        $pipeline = new Pipeline(['foo', 20]);

        $ret = $pipeline->typeCast('string');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals(['foo', '20'], $result);
    }

    public function testTypeCastDefaultMessage()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("Unable to cast to string, bool(false) given; index int(1)");

        $pipeline = new Pipeline(['foo', false, 'bar']);

        $ret = $pipeline->typeCast('string');
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }

    public function testTypeCastCustomError()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("bool(false) should be string for index int(1)");

        $pipeline = new Pipeline(['foo', false, 'bar']);

        $ret = $pipeline->typeCast('string', new \InvalidArgumentException('%s should be %3$s for index %2$s'));
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }


    public function testExpectType()
    {
        $pipeline = new Pipeline(['foo', 'bar']);

        $ret = $pipeline->expectType('string');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals(['foo', 'bar'], $result);
    }

    public function testExpectTypeDefaultMessage()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Expected all elements to be of type string, int(20) given");

        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->expectType('string');
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }

    public function testExpectTypeCustomMessage()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("int(20) should be string for index int(1)");

        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->expectType('string', '%s should be %3$s for index %2$s');
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }

    public function testExpectTypeCustomError()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("int(20) should be string for index int(1)");

        $pipeline = new Pipeline(['foo', 20, 'bar']);

        $ret = $pipeline->expectType('string', new \TypeError('%s should be %3$s for index %2$s'));
        $this->assertSame($pipeline, $ret);

        $pipeline->toArray();
    }
}
