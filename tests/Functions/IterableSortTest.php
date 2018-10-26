<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_sort;

/**
 * @covers \Improved\iterable_sort
 */
class IterableSortTest extends TestCase
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
        $values = $this->sorted;
        shuffle($values);

        return $this->provideIterables($values);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_sort($values, \SORT_STRING);
        $result = iterator_to_array($iterator);

        $this->assertSame($this->sorted, $result);
    }

    public function testSortFlags()
    {
        $values = [
            'img1.png',
            'img10.png',
            'img12.png',
            'img2.png'
        ];

        $iterator = iterable_sort($values, \SORT_NATURAL);

        $result = iterator_to_array($iterator);

        $expected = [
            'img1.png',
            'img2.png',
            'img10.png',
            'img12.png'
        ];
        $this->assertSame($expected, $result);
   }

    public function testPreserveKeys()
    {
        $values = [
            'one' => 'India',
            'two' => 'Zulu',
            'three' => 'Papa',
            'four' => 'Bravo'
        ];

        $iterator = iterable_sort($values, \SORT_STRING, true);

        $result = iterator_to_array($iterator);

        $expected = [
            'four' => 'Bravo',
            'one' => 'India',
            'three' => 'Papa',
            'two' => 'Zulu'
        ];

        $this->assertSame($expected, $result);
    }

    public function testGenerator()
    {
        $keys = [(object)['a' => 'a'], ['b' => 'b'], null, 'd', 'd'];
        $values = [['n' => 'India'], ['n' => 'Zulu'], ['n' => 'Papa'], ['n' => 'Bravo'], ['n' => 'Foxtrot']];

        $loop = function($keys, $values) {
            foreach ($keys as $i => $key) {
                yield $key => $values[$i];
            }
        };

        $generator = $loop($keys, $values);
        $iterator = iterable_sort($generator, function ($a, $b) {
            return $a['n'] <=> $b['n'];
        }, true);

        $resultKeys = [];
        $resultValues = [];

        foreach ($iterator as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        $this->assertSame([$values[3], $values[4], $values[0], $values[2], $values[1]], $resultValues);
        $this->assertSame([$keys[3], $keys[4], $keys[0], $keys[2], $keys[1]], $resultKeys);
    }

    public function testCallback()
    {
        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $iterator = iterable_sort($this->sorted, $compare);
        $result = iterator_to_array($iterator);

        $expected = $this->sorted;
        usort($expected, $compare);

        $this->assertSame($expected, $result);
    }

    public function testCallbackPreserveKeys()
    {
        $values = [
            'one' => 'India',
            'two' => 'Zulu',
            'three' => 'Papa',
            'four' => 'Bravo'
        ];

        $compare = function($a, $b) {
            return (strlen($a) <=> strlen($b)) ?: $a <=> $b;
        };

        $iterator = iterable_sort($values, $compare, true);
        $result = iterator_to_array($iterator);

        $expected = [
            'three' => 'Papa',
            'two' => 'Zulu',
            'four' => 'Bravo',
            'one' => 'India'
        ];

        $this->assertSame($expected, $result);
    }

    public function testEmpty()
    {
        $iterator = iterable_sort(new \EmptyIterator(), \SORT_STRING);
        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_sort($iterator, \SORT_STRING);

        $this->assertTrue(true, "No warning");
    }
}
