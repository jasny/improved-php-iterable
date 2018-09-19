<?php

namespace Jasny\Iterator\Tests;

use Jasny\Iterator\AssertTypeIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Iterator\AssertTypeIterator
 */
class AssertTypeIteratorTest extends TestCase
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
    public function testIterateValid(array $values, $type)
    {
        $inner = new \ArrayIterator($values);

        $iterator = new AssertTypeIterator($inner, $type);

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
        $inner = new \ArrayIterator($values);

        $iterator = new AssertTypeIterator($inner, 'string');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected integer, string given
     */
    public function testSecondInvalid()
    {
        $values = [1, 'hello'];
        $inner = new \ArrayIterator($values);

        $iterator = new AssertTypeIterator($inner, 'integer');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Expected element to be string, stdClass object given
     */
    public function testTypeError()
    {
        $values = [new \stdClass()];
        $inner = new \ArrayIterator($values);

        $message = "Expected element to be string, %s given";
        $iterator = new AssertTypeIterator($inner, 'string', \TypeError::class, $message);

        iterator_to_array($iterator);
    }

    public function testIterateEmpty()
    {
        $iterator = new AssertTypeIterator(new \EmptyIterator(), 'int');

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
