<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_flatten;

/**
 * @covers \Improved\iterable_flatten
 */
class IterableFlattenTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function test()
    {
        $values = [
            ['one', 'two'],
            ['three', 'four', 'five'],
            [],
            'six',
            ['seven']
        ];

        $iterator = iterable_flatten($values);

        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six', 'seven'];
        $this->assertEquals($expected, $result);
    }

    public function testKeys()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos'],
            ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            [],
            'six' => 'seis',
            ['seven' => 'sept']
        ];

        $iterator = iterable_flatten($values, true);

        $result = iterator_to_array($iterator);

        $expected = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco',
            'six' => 'seis', 'seven' => 'sept'];
        $this->assertEquals($expected, $result);
    }

    public function testNoKeys()
    {
        $values = [
            ['one' => 'uno', 'two' => 'dos'],
            ['three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            [],
            'six' => 'seis',
            ['seven' => 'sept']
        ];

        $iterator = iterable_flatten($values, false);

        $result = iterator_to_array($iterator);

        $expected = ['uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'sept'];
        $this->assertEquals($expected, $result);
    }

    public function iteratorsProvider()
    {
        $values = [
            new \ArrayIterator(['one', 'two']),
            new \ArrayObject(['three', 'four', 'five']),
            new \EmptyIterator(),
            'six',
            ['seven']
        ];

        return $this->provideIterables($values, true);
    }

    /**
     * @dataProvider iteratorsProvider
     */
    public function testIterators($values)
    {
        $iterator = iterable_flatten($values);
        $result = iterator_to_array($iterator);

        $expected = ['one', 'two', 'three', 'four', 'five', 'six', 'seven'];
        $this->assertEquals($expected, $result);
    }
    
    public function testNonScalarKeys()
    {
        $generator = function ($keys, $values) {
            foreach ($keys as $i => $key) {
                yield $key => $values[$i];
            }
        };

        $values = $generator(
            [0, 1, null, (object)[]],
            [
                $generator(['d', 2.4], ['one', 'two']),
                ['d' => 'three'],
                'four',
                'five'
            ]
        );

        $iterator = iterable_flatten($values, true);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertEquals(['d', 2.4, 'd', null, (object)[]], $resultKeys);
        $this->assertEquals(['one', 'two', 'three', 'four', 'five'], $resultValues);
    }

    public function testEmpty()
    {
        $iterator = iterable_flatten(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_flatten($iterator);

        $this->assertTrue(true, "No warning");
    }
}
