<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_reshape;

/**
 * @covers \Improved\iterable_reshape
 */
class IterableReshapeTest extends TestCase
{
    use LazyExecutionIteratorTrait;

    public function fullProvider()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            ['three' => 'san', 'one' => 'yi', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            ['two' => 'twee', 'five' => 'vijf', 'three' => 'drie', 'four' => 'vier']
        ];

        $expected = [
            ['one' => 'uno', 'five' => 'cinco', 'III' => 'tres', 0 => 'cuatro'],
            ['one' => 'yi', 'five' => 'wu', 'III' => 'san', 0 => 'si'],
            ['five' => 'vijf', 'III' => 'drie', 0 => 'vier']
        ];

        $objects = [(object)$values[0], (object)$values[1], (object)$values[2]];
        $objectsExpected = [(object)$expected[0], (object)$expected[1], (object)$expected[2]];

        $mixed = [$values[0], (object)$values[1], new \ArrayObject($values[2])];
        $mixedExpected = [$expected[0], (object)$expected[1], new \ArrayObject($expected[2])];

        return [
            [$values, $expected],
            [$objects, $objectsExpected],
            [$mixed, $mixedExpected]
        ];
    }

    /**
     * @dataProvider fullProvider
     */
    public function test(array $values, array $expected)
    {
        $columns = ['one' => true, 'two' => false, 'three' => 'III', 'four' => 0];
        $iterator = iterable_reshape($values, $columns);

        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider fullProvider
     */
    public function testIterator(array $values, array $expected)
    {
        $inner = new \ArrayIterator($values);

        $columns = ['one' => true, 'two' => false, 'three' => 'III', 'four' => 0];
        $iterator = iterable_reshape($inner, $columns);

        $result = iterator_to_array($iterator);
        $this->assertEquals($expected, $result);
    }

    public function testObjects()
    {
        $values = [
            (object)['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            (object)['three' => 'san', 'one' => 'yi', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            (object)['two' => 'twee', 'five' => 'vijf', 'three' => 'drie', 'four' => 'vier']
        ];

        $expected = [
            (object)['one' => 'uno', 'five' => 'cinco', 'III' => 'tres', 0 => 'cuatro'],
            (object)['one' => 'yi', 'five' => 'wu', 'III' => 'san', 0 => 'si'],
            (object)['five' => 'vijf', 'III' => 'drie', 0 => 'vier']
        ];

        $columns = ['one' => true, 'two' => false, 'three' => 'III', 'four' => 0];
        $iterator = iterable_reshape($values, $columns);

        $result = iterator_to_array($iterator);

        $this->assertSame($values, $result); // Still the same objects
        $this->assertEquals($expected, $result);
    }


    public function partialProvider()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            ['three' => 'san', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            ['two' => 'twee', 'four' => 'vier']
        ];

        $expected = [
            ['one' => 'uno', 'III' => 'tres', 'five' => 'cinco', 0 => 'cuatro'],
            ['III' => 'san', 'five' => 'wu', 0 => 'si'],
            [0 => 'vier']
        ];

        $objects = [(object)$values[0], (object)$values[1], (object)$values[2]];
        $objectsExpected = [(object)$expected[0], (object)$expected[1], (object)$expected[2]];

        $mixed = [$values[0], (object)$values[1], new \ArrayObject($values[2])];
        $mixedExpected = [$expected[0], (object)$expected[1], new \ArrayObject($expected[2])];

        return [
            [$values, $expected],
            [$objects, $objectsExpected],
            [$mixed, $mixedExpected]
        ];
    }

    /**
     * @dataProvider partialProvider
     */
    public function testPartial(array $values, array $expected)
    {
        $columns = ['one' => true, 'two' => false, 'three' => 'III', 'four' => 0];
        $iterator = iterable_reshape($values, $columns);

        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    public function testScalars()
    {
        $date = new \DateTime();

        $values = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            null,
            42,
            'hello',
            $date,
            (object)['two' => 'twee', 'five' => 'vijf', 'three' => 'drie', 'four' => 'vier']
        ];

        $columns = ['one' => true, 'two' => false, 'three' => 'III', 'four' => 0];
        $iterator = iterable_reshape($values, $columns);

        $result = iterator_to_array($iterator);

        $expected = [
            ['one' => 'uno', 'five' => 'cinco', 'III' => 'tres', 0 => 'cuatro'],
            null,
            42,
            'hello',
            $date,
            (object)['five' => 'vijf', 'III' => 'drie', 0 => 'vier']
        ];

        $this->assertEquals($expected, $result);
    }

    public function testEmpty()
    {
        $columns = ['one' => true, 'two' => false, 'three' => 'III', 'four' => 0];

        $iterator = iterable_reshape(new \EmptyIterator(), $columns);
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_reshape($iterator, ['foo' => true]);

        $this->assertTrue(true, "No warning");
    }
}
