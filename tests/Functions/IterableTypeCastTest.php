<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_type_cast;

/**
 * @covers \Improved\iterable_type_cast
 */
class IterableTypeCastTest extends TestCase
{
    use LazyExecutionIteratorTrait;

    public function validProvider()
    {
        return [
            [['hello', 'world'], 'string', ['hello', 'world']],
            [[1, '2', 3.0], 'int', [1, 2, 3]],
            [['hello', 2, 'you', null], 'string', ['hello', '2', 'you', '']],
            [['hello', 2, 'you', null], '?string', ['hello', '2', 'you', null]],
            [[new \stdClass()], \stdClass::class],
            [[new \DateTime()], \DateTimeInterface::class]
        ];
    }

    /**
     * @dataProvider validProvider
     */
    public function test(array $values, $type, $expected = null)
    {
        $iterator = iterable_type_cast($values, $type);
        $result = iterator_to_array($iterator);

        $this->assertSame($expected ?? $values, $result);
    }

    /**
     * @dataProvider validProvider
     */
    public function testIteratorValid(array $values, $type, $expected = null)
    {
        $inner = new \ArrayIterator($values);

        $iterator = iterable_type_cast($inner, $type);
        $result = iterator_to_array($iterator);

        $this->assertSame($expected ?? $values, $result);
    }

    public function testFirstInvalid()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("Unable to cast to string, bool(true) given; index int(2)");

        $values = [1, 'hello', true];

        $iterator = iterable_type_cast($values, 'string');

        iterator_to_array($iterator);
    }

    public function testSecondInvalid()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("Unable to cast to integer, string(5) \"hello\" given; index int(1)");

        $values = [1, 'hello', true];

        $iterator = iterable_type_cast($values, 'integer');

        iterator_to_array($iterator);
    }

    public function testTypeThrowable()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("instance of stdClass - int(0) - string");

        $values = [new \stdClass()];

        $message = '%s - %s - %s';
        $iterator = iterable_type_cast($values, '?string', new \UnexpectedValueException($message));

        iterator_to_array($iterator);
    }


    public function testEmpty()
    {
        $iterator = iterable_type_cast(new \EmptyIterator(), 'int');

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_type_cast($iterator, 'int');

        $this->assertTrue(true, "No warning");
    }
}
