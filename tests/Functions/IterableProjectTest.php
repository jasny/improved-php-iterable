<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_project;

/**
 * @covers \Improved\iterable_project
 */
class IterableProjectTest extends TestCase
{
    use LazyExecutionIteratorTrait;

    public function fullProvider()
    {
        $arrays = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            ['three' => 'san', 'one' => 'yi', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            ['two' => 'twee', 'five' => 'vijf', 'one' => 'één', 'three' => 'drie', 'four' => 'vier']
        ];

        $objects = [
            (object)['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            (object)['three' => 'san', 'one' => 'yi', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            (object)['two' => 'twee', 'five' => 'vijf', 'one' => 'één', 'three' => 'drie', 'four' => 'vier']
        ];

        $mixed = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            (object)['three' => 'san', 'one' => 'yi', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            new \ArrayObject(['two' => 'twee', 'five' => 'vijf', 'one' => 'één', 'three' => 'drie', 'four' => 'vier'])
        ];

        return [
            [$arrays],
            [$objects],
            [$mixed]
        ];
    }

    /**
     * @dataProvider fullProvider
     */
    public function test(array $values)
    {
        $map = ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four', 'V' => 'five'];

        $iterator = iterable_project($values, $map);

        $result = iterator_to_array($iterator);

        $expected = [
            ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'cuatro', 'V' => 'cinco'],
            ['I' => 'yi', 'II' => 'er', 'III' => 'san', 'IV' => 'si', 'V' => 'wu'],
            ['I' => 'één', 'II' => 'twee', 'III' => 'drie', 'IV' => 'vier', 'V' => 'vijf']
        ];

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider fullProvider
     */
    public function testIterator(array $values)
    {
        $inner = new \ArrayIterator($values);

        $map = ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four', 'V' => 'five'];

        $iterator = iterable_project($inner, $map);

        $result = iterator_to_array($iterator);

        $expected = [
            ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'cuatro', 'V' => 'cinco'],
            ['I' => 'yi', 'II' => 'er', 'III' => 'san', 'IV' => 'si', 'V' => 'wu'],
            ['I' => 'één', 'II' => 'twee', 'III' => 'drie', 'IV' => 'vier', 'V' => 'vijf']
        ];

        $this->assertSame($expected, $result);
    }

    public function partialProvider()
    {
        $arrays = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            ['three' => 'san', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            ['two' => 'twee', 'four' => 'vier']
        ];

        $objects = [
            (object)['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            (object)['three' => 'san', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            (object)['two' => 'twee', 'four' => 'vier']
        ];

        $mixed = [
            ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            (object)['three' => 'san', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            new \ArrayObject(['two' => 'twee', 'four' => 'vier'])
        ];

        return [
            [$arrays],
            [$objects],
            [$mixed]
        ];
    }

    /**
     * @dataProvider partialProvider
     */
    public function testPartial(array $values)
    {
        $map = ['one', 'three', 'four'];

        $iterator = iterable_project($values, $map);

        $result = iterator_to_array($iterator);

        $expected = [
            ['uno', 'tres', 'cuatro'],
            [null, 'san', 'si'],
            [null, null, 'vier']
        ];

        $this->assertSame($expected, $result);
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
            (object)['two' => 'twee', 'five' => 'vijf', 'one' => 'één', 'three' => 'drie', 'four' => 'vier']
        ];
        $inner = new \ArrayIterator($values);

        $map = ['I' => 'one', 'III' => 'three', 'IV' => 'four'];

        $iterator = iterable_project($inner, $map);

        $result = iterator_to_array($iterator);

        $expected = [
            ['I' => 'uno', 'III' => 'tres', 'IV' => 'cuatro'],
            ['I' => null, 'III' => null, 'IV' => null],
            ['I' => null, 'III' => null, 'IV' => null],
            ['I' => null, 'III' => null, 'IV' => null],
            ['I' => null, 'III' => null, 'IV' => null],
            ['I' => 'één', 'III' => 'drie', 'IV' => 'vier']
        ];

        $this->assertSame($expected, $result);
    }

    public function testEmpty()
    {
        $map = ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four', 'V' => 'five'];

        $iterator = iterable_project(new \EmptyIterator(), $map);
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_project($iterator, ['foo']);

        $this->assertTrue(true, "No warning");
    }
}
