<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_to_iterator;

/**
 * @covers \Improved\iterable_to_iterator
 */
class IterableToIteratorTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three']);
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $iterator = iterable_to_iterator($values);
        $this->assertInstanceOf(\Iterator::class, $iterator);

        $result = iterator_to_array($iterator);
        $this->assertSame($expected, $result);
    }

    public function testNestedIteratorAggregate()
    {
        $values = new class() implements \IteratorAggregate {
            public function getIterator()
            {
                return new \ArrayObject(['uno', 'dos', 'tres']);
            }
        };

        $iterator = iterable_to_iterator($values);
        $this->assertInstanceOf(\Iterator::class, $iterator);

        $result = iterator_to_array($iterator);
        $this->assertSame(['uno', 'dos', 'tres'], $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_to_iterator(new \EmptyIterator());
        $this->assertInstanceOf(\Iterator::class, $iterator);

        $result = iterator_to_array($iterator);
        $this->assertEquals([], $result);
    }
}
