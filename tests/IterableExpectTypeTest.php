<?php

declare(strict_types=1);

namespace Ipl\Tests;

use PHPUnit\Framework\TestCase;
use function Ipl\iterable_expect_type;

/**
 * @covers \Ipl\iterable_expect_type
 */
class IterableExpectTypeTest extends TestCase
{
    use LazyExecutionIteratorTrait;

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
    public function test(array $values, $type)
    {
        $iterator = iterable_expect_type($values, $type);
        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Untouched
    }

    /**
     * @dataProvider validProvider
     */
    public function testIteratorValid(array $values, $type)
    {
        $inner = new \ArrayIterator($values);

        $iterator = iterable_expect_type($inner, $type);
        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Untouched
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected all elements to be of type string, integer given
     */
    public function testFirstInvalid()
    {
        $values = [1, 'hello'];

        $iterator = iterable_expect_type($values, 'string');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected all elements to be of type integer, string given
     */
    public function testSecondInvalid()
    {
        $values = [1, 'hello'];

        $iterator = iterable_expect_type($values, 'integer');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage FOO BOO stdClass object WOO
     */
    public function testTypeError()
    {
        $values = [new \stdClass()];

        $message = "FOO BOO %s WOO";
        $iterator = iterable_expect_type($values, 'string', \TypeError::class, $message);

        iterator_to_array($iterator);
    }

    public function testEmpty()
    {
        $iterator = iterable_expect_type(new \EmptyIterator(), 'int');

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_expect_type($iterator, 'int');

        $this->assertTrue(true, "No warning");
    }
}
