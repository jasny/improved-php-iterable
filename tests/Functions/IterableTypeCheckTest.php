<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_type_check;

/**
 * @covers \Improved\iterable_type_check
 */
class IterableTypeCheckTest extends TestCase
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
        $iterator = iterable_type_check($values, $type);
        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Untouched
    }

    /**
     * @dataProvider validProvider
     */
    public function testIteratorValid(array $values, $type)
    {
        $inner = new \ArrayIterator($values);

        $iterator = iterable_type_check($inner, $type);
        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Untouched
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Expected string, int(1) given; index int(0)
     */
    public function testFirstInvalid()
    {
        $values = [1, 'hello'];

        $iterator = iterable_type_check($values, 'string');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Expected integer, string(5) "hello" given; index int(1)
     */
    public function testSecondInvalid()
    {
        $values = [1, 'hello'];

        $iterator = iterable_type_check($values, 'integer');

        iterator_to_array($iterator);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage instance of stdClass - int(0) - string or null
     */
    public function testTypeThrowable()
    {
        $values = [new \stdClass()];

        $message = '%s - %s - %s';
        $iterator = iterable_type_check($values, '?string', new \UnexpectedValueException($message));

        iterator_to_array($iterator);
    }


    public function testEmpty()
    {
        $iterator = iterable_type_check(new \EmptyIterator(), 'int');

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_type_check($iterator, 'int');

        $this->assertTrue(true, "No warning");
    }
}
