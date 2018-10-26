<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_first;

/**
 * @covers \Improved\iterable_first
 */
class IterableFirstTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', 'three'], true);
    }

    /**
     * @dataProvider provider
     */
    public function test($values)
    {
        $result = iterable_first($values);

        $this->assertEquals('one', $result);
    }

    public function testNoWalk()
    {
        /** @var \Iterator|MockObject $iterator */
        $iterator = $this->createMock(\Iterator::class);
        $iterator->expects($this->any())->method('valid')->willReturn(true);
        $iterator->expects($this->once())->method('current')->willReturn('one');

        $result = iterable_first($iterator);

        $this->assertEquals('one', $result);
    }


    public function testEmpty()
    {
        $result = iterable_first(new \EmptyIterator());

        $this->assertNull($result);
    }

    /**
     * @expectedException \RangeException
     * @expectedExceptionMessage  Unable to get first element; iterable is empty
     */
    public function testEmptyRequired()
    {
        iterable_first(new \EmptyIterator(), true);
    }
}
