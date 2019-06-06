<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use ArrayIterator;
use ArrayObject;
use Generator;
use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_unwind;

/**
 * @covers \Improved\iterable_unwind
 */
class IterableFlattenTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        $values = [
            ['ref' => 'a', 'numbers' => ['one', 'two']],
            ['ref' => 'b', 'numbers' => new ArrayObject(['three', 'four', 'five'])],
            ['ref' => 'c', 'numbers' => []],
            ['ref' => 'd', 'numbers' => 'six'],
            ['ref' => 'e'],
            ['ref' => 'f', 'numbers' => new ArrayIterator(['foo' => 'seven'])],
        ];

        $expected = [
            ['ref' => 'a', 'numbers' => 'one'],
            ['ref' => 'a', 'numbers' => 'two'],
            ['ref' => 'b', 'numbers' => 'three'],
            ['ref' => 'b', 'numbers' => 'four'],
            ['ref' => 'b', 'numbers' => 'five'],
            ['ref' => 'c', 'numbers' => null],
            ['ref' => 'd', 'numbers' => 'six'],
            ['ref' => 'e'],
            ['ref' => 'f', 'numbers' => 'seven'],
        ];

        $expectedMapKey = [
            ['ref' => 'a', 'numbers' => 'one', 'nrkey' => 0],
            ['ref' => 'a', 'numbers' => 'two', 'nrkey' => 1],
            ['ref' => 'b', 'numbers' => 'three', 'nrkey' => 0],
            ['ref' => 'b', 'numbers' => 'four', 'nrkey' => 1],
            ['ref' => 'b', 'numbers' => 'five', 'nrkey' => 2],
            ['ref' => 'c', 'numbers' => null, 'nrkey' => null],
            ['ref' => 'd', 'numbers' => 'six'],
            ['ref' => 'e'],
            ['ref' => 'f', 'numbers' => 'seven', 'nrkey' => 'foo'],
        ];

        return [
            [
                $values,
                $expected,
                $expectedMapKey,
            ],
            [
                array_map(function ($el) { return (object)$el; }, $values),
                array_map(function ($el) { return (object)$el; }, $expected),
                array_map(function ($el) { return (object)$el; }, $expectedMapKey),
            ],
            [
                array_map(function ($el) { return new ArrayObject($el); }, $values),
                array_map(function ($el) { return new ArrayObject($el); }, $expected),
                array_map(function ($el) { return new ArrayObject($el); }, $expectedMapKey),
            ],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function test($iterable, $expected)
    {
        $iterator = iterable_unwind($iterable, 'numbers');
        $this->assertInstanceOf(Generator::class, $iterator);

        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider provider
     */
    public function testMapKey($iterable, $_, $expected)
    {
        $iterator = iterable_unwind($iterable, 'numbers', 'nrkey');
        $this->assertInstanceOf(Generator::class, $iterator);

        $result = iterator_to_array($iterator);

        $this->assertEquals($expected, $result);
    }

    public function testPreserveKeys()
    {
        $values = [
            'I' => ['ref' => 'a', 'numbers' => ['one', 'two']],
            'II' => ['ref' => 'b', 'numbers' => ['three', 'four', 'five']],
            'III' => ['ref' => 'c', 'numbers' => []],
            'IV' => ['ref' => 'd'],
        ];

        $iterator = iterable_unwind($values, 'numbers', null, true);

        $result = [];
        $resultKeys = [];

        foreach ($iterator as $key => $value) {
            $result[] = $value;
            $resultKeys[] = $key;
        }

        $expected = [
            ['ref' => 'a', 'numbers' => 'one'],
            ['ref' => 'a', 'numbers' => 'two'],
            ['ref' => 'b', 'numbers' => 'three'],
            ['ref' => 'b', 'numbers' => 'four'],
            ['ref' => 'b', 'numbers' => 'five'],
            ['ref' => 'c', 'numbers' => null],
            ['ref' => 'd'],
        ];
        $expectedKeys = ['I', 'I', 'II', 'II', 'II', 'III', 'IV'];

        $this->assertEquals($expected, $result);
        $this->assertEquals($expectedKeys, $resultKeys);
    }

    public function iteratorsProvider()
    {
        $values = [
            ['ref' => 'a', 'numbers' => ['one', 'two']],
            ['ref' => 'b', 'numbers' => new ArrayObject(['three', 'four', 'five'])],
            ['ref' => 'c', 'numbers' => []],
            ['ref' => 'd', 'numbers' => 'six'],
            ['ref' => 'e'],
            ['ref' => 'f', 'numbers' => new ArrayIterator(['seven'])]
        ];

        return $this->provideIterables($values, true);
    }

    /**
     * @dataProvider iteratorsProvider
     */
    public function testIterators($values)
    {
        $iterator = iterable_unwind($values, 'numbers');
        $result = iterator_to_array($iterator);

        $expected = [
            ['ref' => 'a', 'numbers' => 'one'],
            ['ref' => 'a', 'numbers' => 'two'],
            ['ref' => 'b', 'numbers' => 'three'],
            ['ref' => 'b', 'numbers' => 'four'],
            ['ref' => 'b', 'numbers' => 'five'],
            ['ref' => 'c', 'numbers' => null],
            ['ref' => 'd', 'numbers' => 'six'],
            ['ref' => 'e'],
            ['ref' => 'f', 'numbers' => 'seven'],
        ];

        $this->assertEquals($expected, $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_unwind(new \EmptyIterator(), 'numbers');

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_unwind($iterator, 'numbers');

        $this->assertTrue(true, "No warning");
    }
}
