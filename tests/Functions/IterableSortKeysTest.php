<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_sort_keys;

/**
 * @covers \Improved\iterable_sort_keys
 */
class IterableSortKeysTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    protected $sorted = [
        "Alpha",
        "Bravo",
        "Charlie",
        "Delta",
        "Echo",
        "Foxtrot",
        "Golf",
        "Hotel",
        "India",
        "Juliet",
        "Kilo",
        "Lima",
        "Mike",
        "November",
        "Oscar",
        "Papa",
        "Quebec",
        "Romeo",
        "Sierra",
        "Tango",
        "Uniform",
        "Victor",
        "Whiskey",
        "X-ray",
        "Yankee",
        "Zulu"
    ];

    public function provider()
    {
        $keys = $this->sorted;
        shuffle($keys);

        $values = array_fill_keys($keys, null);

        return $this->provideIterables($values, false, false);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_sort_keys($values, \SORT_STRING);
        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, array_keys($result));
    }

    public function testKey()
    {
        $values = [
            'India' => 'one',
            'Zulu' => 'two',
            'Papa' => 'three',
            'Bravo' => 'four'
        ];

        $iterator = iterable_sort_keys($values, \SORT_STRING);
        $result = iterator_to_array($iterator);

        $expected = [
            'Bravo' => 'four',
            'India' => 'one',
            'Papa' => 'three',
            'Zulu' => 'two'
        ];

        $this->assertSame($expected, $result);
    }

    public function testGenerator()
    {
        $keys = [['i' => 7], ['i' => 2], null, ['i' => 42], ['i' => -2]];

        $loop = function($keys) {
            foreach ($keys as $i => $key) {
                yield $key => $i;
            }
        };

        $generator = $loop($keys);
        $iterator = iterable_sort_keys($generator, function($a, $b) {
            return ($a['i'] ?? 0) <=> ($b['i'] ?? 0);
        });

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([4, 2, 1, 0, 3], $resultValues);
        $this->assertSame([$keys[4], $keys[2], $keys[1], $keys[0], $keys[3]], $resultKeys);
    }

    public function testCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $inner = new \ArrayIterator(array_fill_keys($this->sorted, null));

        $iterator = iterable_sort_keys($inner, $compare);
        $result = iterator_to_array($iterator);

        $expected = array_fill_keys($this->sorted, null);
        uksort($expected, $compare);

        $this->assertSame($expected, $result);
    }
    
    public function testEmpty()
    {
        $iterator = iterable_sort_keys(new \EmptyIterator(), \SORT_STRING);

        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_sort_keys($iterator, \SORT_STRING);

        $this->assertTrue(true, "No warning");
    }
}
