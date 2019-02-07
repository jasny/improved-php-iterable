<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_reduce;

/**
 * @covers \Improved\iterable_reduce
 */
class IterableReduceTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables([2, 3, 4], true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_reduce($values, function ($product, $value) {
            return $product * $value;
        }, 1);

        $this->assertEquals(24, $result);
    }

    public function keyProvider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three'], false, false);
    }

    /**
     * @dataProvider keyProvider
     */
    public function testWithKeys($values)
    {
        $result = iterable_reduce($values, function ($list, $value, $key) {
            return $list . sprintf('{%s:%s}', $key, $value);
        }, '');

        $this->assertEquals('{I:one}{II:two}{III:three}', $result);
    }

    public function testEmpty()
    {
        $result = iterable_reduce(new \EmptyIterator(), function () {
            return 10;
        }, 1);

        $this->assertEquals(1, $result);
    }
}
