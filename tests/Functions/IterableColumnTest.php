<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_column;

/**
 * @covers \Improved\iterable_column
 */
class IterableColumnTest extends TestCase
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
        $iterator = iterable_column($values, 'three');

        $result = iterator_to_array($iterator);
        $this->assertSame(['tres', 'san', 'drie'], $result);
    }

    /**
     * @dataProvider fullProvider
     */
    public function testKey(array $values)
    {
        $iterator = iterable_column($values, null, 'three');

        $result = iterator_to_array($iterator);

        $expected = ['tres' => $values[0], 'san' => $values[1], 'drie' => $values[2]];
        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider fullProvider
     */
    public function testBoth(array $values)
    {
        $iterator = iterable_column($values, 'three', 'one');

        $result = iterator_to_array($iterator);
        $this->assertSame(['uno' => 'tres', 'yi' => 'san', 'één' => 'drie'], $result);
    }

    /**
     * @dataProvider fullProvider
     */
    public function testIterator(array $values)
    {
        $inner = new \ArrayIterator($values);
        $iterator = iterable_column($inner, 'three');

        $result = iterator_to_array($iterator);
        $this->assertSame(['tres', 'san', 'drie'], $result);
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
        $iterator = iterable_column($values, 'three');

        $result = iterator_to_array($iterator);
        $this->assertSame(['tres', 'san', null], $result);
    }

    /**
     * @dataProvider partialProvider
     */
    public function testPartialKey(array $values)
    {
        $iterator = iterable_column($values, null, 'three');

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(['tres', 'san', null], $resultKeys);
        $this->assertSame($values, $resultValues);
    }

    /**
     * @dataProvider partialProvider
     */
    public function testPartialBoth(array $values)
    {
        $iterator = iterable_column($values, 'three', 'one');

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame(['uno', null, null], $resultKeys);
        $this->assertSame(['tres', 'san', null], $resultValues);
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

        $iterator = iterable_column($inner, 'three');

        $result = iterator_to_array($iterator);
        $this->assertSame(['tres', null, null, null, null, 'drie'], $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_column(new \EmptyIterator(), 'one');
        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_column($iterator,'one');

        $this->assertTrue(true, "No warning");
    }
}
