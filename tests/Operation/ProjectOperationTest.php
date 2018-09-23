<?php

namespace Jasny\IteratorPipeline\Tests;

use Jasny\IteratorPipeline\Operation\ProjectOperation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Operation\ProjectOperation
 * @covers \Jasny\IteratorPipeline\Operation\AbstractOperation
 */
class ProjectOperationTest extends TestCase
{
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
    public function testIterate(array $values)
    {
        $map = ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four', 'V' => 'five'];

        $iterator = new ProjectOperation($values, $map);

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
    public function testIterateIterator(array $values)
    {
        $inner = new \ArrayIterator($values);

        $map = ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four', 'V' => 'five'];

        $iterator = new ProjectOperation($inner, $map);

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
    public function testIterateFill(array $values)
    {
        $map = ['one', 'three', 'four'];

        $iterator = new ProjectOperation($values, $map);

        $result = iterator_to_array($iterator);

        $expected = [
            ['uno', 'tres', 'cuatro'],
            [null, 'san', 'si'],
            [null, null, 'vier']
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateScalars()
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

        $map = ['one', 'three', 'four'];

        $iterator = new ProjectOperation($inner, $map);

        $result = iterator_to_array($iterator);

        $expected = [
            ['uno', 'tres', 'cuatro'],
            null,
            42,
            'hello',
            $date,
            ['één', 'drie', 'vier']
        ];

        $this->assertSame($expected, $result);
    }

    public function testIterateEmpty()
    {
        $map = ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' => 'four', 'V' => 'five'];

        $iterator = new ProjectOperation(new \EmptyIterator(), $map);

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }
}
