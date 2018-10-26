<?php declare(strict_types=1);

namespace Improved\Tests\Functions;

use Improved\Tests\ProvideIterablesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Improved\iterable_find_key;

/**
 * @covers \Improved\iterable_find_key
 */
class IterableFindKeyTest extends TestCase
{
    use ProvideIterablesTrait;

    public function provider()
    {
        return $this->provideIterables(['I' => 'one', 'II' => 'two', 'III' => 'three'], false, false);
    }

    /**
     * @dataProvider provider
     */
    public function testValue($values)
    {
        $result = iterable_find_key($values, function($value) {
            return substr($value, 0, 1) === 't';
        });

        $this->assertEquals('II', $result);
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
        $result = iterable_find_key($values, function($value, $key) {
            return substr($key, 0, 1) === 't';
        });

        $this->assertEquals('two', $result);
    }

    /**
     * @dataProvider provider
     */
    public function testNotFound($values)
    {
        $result = iterable_find_key($values, function() {
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
        $iterator->expects($this->exactly(2))->method('key')
            ->willReturnOnConsecutiveCalls('I', 'II');

        $result = iterable_find_key($iterator, function($value) {
            return $value === 'two';
        });

        $this->assertEquals('II', $result);
    }

    public function testEmpty()
    {
        $result = iterable_find_key(new \EmptyIterator(), function() {});

        $this->assertNull($result);
    }
}
