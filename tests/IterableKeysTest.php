<?php

declare(strict_types=1);

namespace Ipl\Tests;

use PHPUnit\Framework\TestCase;
use function Ipl\iterable_keys;

/**
 * @covers \Ipl\iterable_keys
 */
class IterableKeysTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        $assoc = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'];
        $tests = $this->provideIterables($assoc, false);

        foreach ($tests as &$test) {
            $test[1] = array_keys($test[1]);
        }

        return $tests;
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $iterator = iterable_keys($values);
        $result = iterator_to_array($iterator);

        $this->assertSame($expected, $result);
    }

    public function testGenerator()
    {
        $keys = [null, [], (object)[], 22, -1, 'a', 'a'];

        $generate = function($keys) {
            foreach ($keys as $key) {
                yield $key => null;
            }
        };

        $iterator = iterable_keys($generate($keys));
        $result = iterator_to_array($iterator);

        $this->assertSame($keys, $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_keys(new \EmptyIterator());

        $result = iterator_to_array($iterator);

        $this->assertEquals([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_keys($iterator);

        $this->assertTrue(true, "No warning");
    }
}
