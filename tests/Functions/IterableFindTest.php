<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_find;

/**
 * @covers \Improved\iterable_find
 */
class IterableFindTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', 'three'], true);
    }

    /**
     * @dataProvider provider
     */
    public function testValue($values)
    {
        $result = iterable_find($values, function($value) {
            return substr($value, 0, 1) === 't';
        });

        $this->assertEquals('two', $result);
    }

    public function keyProvider()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        return $this->provideIterables($values, false, false);
    }

    /**
     * @dataProvider keyProvider
     */
    public function testKey($values)
    {
        $result = iterable_find($values, function($value, $key) {
            return substr($key, 0, 1) === 't';
        });

        $this->assertEquals('dos', $result);
    }

    /**
     * @dataProvider provider
     */
    public function testNotFound($values)
    {
        $result = iterable_find($values, function() {
            return false;
        });

        $this->assertNull($result);
    }

    public function testNoWalk()
    {
        /** @var \Iterator|MockObject $iterator */
        $iterator = $this->createMock(\Iterator::class);
        $iterator->expects($this->any())->method('valid')->willReturn(true);
        $iterator->expects($this->exactly(2))->method('current')
            ->willReturnOnConsecutiveCalls('one', 'two');

        $result = iterable_find($iterator, function($value) {
            return $value === 'two';
        });

        $this->assertEquals('two', $result);
    }

    public function testEmpty()
    {
        $result = iterable_find(new \EmptyIterator(), function() {});

        $this->assertNull($result);
    }
}
