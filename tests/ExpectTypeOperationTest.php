<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Operation\ExpectTypeOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\ExpectTypeOperation
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class ExpectTypeOperationTest extends TestCase
{
    public function validProvider()
    {
        return [
            [['hello', 'world'], 'string'],
            [[1, 2, 3], 'int'],
            [['hello', 2, 'you'], ['string', 'int']],
            [[new \stdClass()], \stdClass::class],
            [[new \DateTime()], \DateTimeInterface::class]
        ];
    }

    /**
     * @dataProvider validProvider
     */
    public function testIterate(array $values, $type)
    {
        $iterator = new ExpectTypeOperation($values, $type);

        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Untouched
    }

    /**
     * @dataProvider validProvider
     */
    public function testIterateIteratorValid(array $values, $type)
    {
        $inner = new \ArrayIterator($values);

        $iterator = new ExpectTypeOperation($inner, $type);

        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Untouched
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected string, integer given
     */
    public function testFirstInvalid()
    {
        $values = [1, 'hello'];

        $iterator = new ExpectTypeOperation($values, 'string');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected integer, string given
     */
    public function testSecondInvalid()
    {
        $values = [1, 'hello'];

        $iterator = new ExpectTypeOperation($values, 'integer');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Expected element to be string, stdClass object given
     */
    public function testTypeError()
    {
        $values = [new \stdClass()];

        $message = "Expected element to be string, %s given";
        $iterator = new ExpectTypeOperation($values, 'string', \TypeError::class, $message);

        iterator_to_array($iterator);
    }

    public function testIterateEmpty()
    {
        $iterator = new ExpectTypeOperation(new \EmptyIterator(), 'int');

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
