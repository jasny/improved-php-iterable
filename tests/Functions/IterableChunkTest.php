<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\LazyExecutionIteratorTrait;
use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_chunk;

/**
 * @covers \Improved\iterable_chunk
 */
class IterableChunkTest extends TestCase
{
    use ProvideIterablesTrait;
    use LazyExecutionIteratorTrait;

    public function provider()
    {
        return $this->provideIterables(array_fill(0, 45, null), true, true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $iterator = iterable_chunk($values, 10);
        $this->assertInstanceOf(\Traversable::class, $iterator);

        $lenghts = [];

        foreach ($iterator as $chunk) {
            $lenght = 0;
            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ($chunk as $_) {
                $lenght++;
            }
            $lenghts[] = $lenght;
        }

        $this->assertEquals([10, 10, 10, 10, 5], $lenghts);
    }

    public function oneChunkProvider()
    {
        return $this->provideIterables(array_fill(0, 10, null));
    }

    /**
     * @dataProvider oneChunkProvider
     */
    public function testOneChunk($values)
    {
        $iterator = iterable_chunk($values, 10);
        $this->assertInstanceOf(\Traversable::class, $iterator);

        $lenghts = [];

        foreach ($iterator as $chunk) {
            $lenghts[] = count(iterator_to_array($chunk, false));
        }

        $this->assertEquals([10], $lenghts);
    }


    public function testIterateEmpty()
    {
        $iterator = iterable_chunk(new \EmptyIterator(), 10);
        $result = iterator_to_array($iterator);

        $this->assertSame([], $result);
    }

    /**
     * Test that nothing happens when not iterating
     */
    public function testLazyExecution()
    {
        $iterator = $this->createLazyExecutionIterator();

        iterable_chunk($iterator, 10);

        $this->assertTrue(true, "No warning");
    }
}
