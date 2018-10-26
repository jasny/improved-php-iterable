<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\IteratorPipeline\Pipeline;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_to_array;

/**
 * @covers \Improved\iterable_to_array
 */
class IterableToArrayTest extends TestCase
{
    use ProvideIterablesTrait {
        provideIterables as private _provideIterables;
    }

    public function provideIterables(array $values, $tricky = false, $fixedArray = true)
    {
        $iterables = $this->_provideIterables($values, $tricky, $fixedArray);

        $iterables[] = [
            Pipeline::with(['one' => 'I', 'two' => 'II', 'three' => 'III'])->flip(),
            ['I' => 'one', 'II' => 'two', 'III' => 'three']
        ];

        return $iterables;
    }

    public function provider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three'], true);
    }

    public function noTrickyProvider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three'], false);
    }

    /**
     * @dataProvider provider
     */
    public function test($values, $expected)
    {
        $result = iterable_to_array($values);

        if ($values instanceof \Generator || $values instanceof Pipeline) {
            $expected = array_values($expected);
        }

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider noTrickyProvider
     */
    public function testPreverseKeys($values, $expected)
    {
        $result = iterable_to_array($values, true);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider provider
     */
    public function testNoKeys($values, $expected)
    {
        $result = iterable_to_array($values, false);

        $this->assertSame(array_values($expected), $result);
    }

    public function testEmpty()
    {
        $result = iterable_to_array(new \EmptyIterator());

        $this->assertEquals([], $result);
    }
}
