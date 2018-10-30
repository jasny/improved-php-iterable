<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_has_none;

/**
 * @covers \Improved\iterable_has_none
 */
class IterableHasNoneTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['one', 'two', 'three'], true);
    }

    /**
     * @dataProvider provider
     */
    public function testTrue($values)
    {
        $result = iterable_has_none($values, function($value) {
            return strpos($value, 'X') !== false;
        });

        $this->assertTrue($result);
    }

    /**
     * @dataProvider provider
     */
    public function testFalse($values)
    {
        $result = iterable_has_none($values, function($value) {
            return $value === 'two';
        });

        $this->assertFalse($result);
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
        $result = iterable_has_none($values, function($value, $key) {
            return $key === 'two';
        });

        $this->assertFalse($result);
    }

    public function testNoWalk()
    {
        /** @var \Iterator|MockObject $iterator */
        $iterator = $this->createMock(\Iterator::class);
        $iterator->expects($this->any())->method('valid')->willReturn(true);
        $iterator->expects($this->exactly(2))->method('current')
            ->willReturnOnConsecutiveCalls('one', 'two');

        $result = iterable_has_none($iterator, function($value) {
            return $value === 'two';
        });

        $this->assertFalse($result);
    }

    public function testEmpty()
    {
        $result = iterable_has_none(new \EmptyIterator(), function() {});

        $this->assertTrue($result);
    }
}
