<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_concat;

/**
 * @covers \Improved\iterable_concat
 */
class IterableConcatTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['a', 'b', 'c', 'd'], true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_concat($values);

        $this->assertEquals('abcd', $result);
    }

    public function testMixed()
    {
        $bind = new class() {
            public function __toString(): string
            {
                return 'bind';
            }
        };

        $values = [1, 'ring', 2, $bind];
        $iterator = new \ArrayIterator($values);

        $result = iterable_concat($iterator);

        $this->assertEquals('1ring2bind', $result);
    }

    public function testGlue()
    {
        $values = ['one', 'ring', 'to', 'bind'];
        $iterator = new \ArrayIterator($values);

        $result = iterable_concat($iterator, '<->');

        $this->assertEquals('one<->ring<->to<->bind', $result);
    }

    public function testEmpty()
    {
        $result = iterable_concat(new \EmptyIterator());

        $this->assertEquals('', $result);
    }
}
