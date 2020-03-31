<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_expect_type;

/**
 * @covers \Improved\iterable_expect_type
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

    public function testFirstInvalid()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Expected all elements to be of type string, int(1) given");

        $values = [1, 'hello'];

        $iterator = iterable_expect_type($values, 'string');

        iterator_to_array($iterator);
    }

    public function testSecondInvalid()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Expected all elements to be of type integer, string(5) \"hello\" given");

        $values = [1, 'hello'];

        $iterator = iterable_expect_type($values, 'integer');

        iterator_to_array($iterator);
    }

    public function testTypeErrorMessage()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("FOO BOO instance of stdClass WOO");

        $values = [new \stdClass()];

        $message = "FOO BOO %s WOO";
        $iterator = iterable_expect_type($values, 'string', $message);

        iterator_to_array($iterator);
    }

    public function testTypeErrorThrowable()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("Expected string, instance of stdClass given");

        $values = [new \stdClass()];

        $iterator = iterable_expect_type($values, 'string', new \TypeError());

        iterator_to_array($iterator);
    }

    public function testTypeError()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("FOO BOO instance of stdClass WOO");

        $values = [new \stdClass()];

        $message = "FOO BOO %s WOO";
        $iterator = iterable_expect_type($values, 'string', new \TypeError($message));

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
