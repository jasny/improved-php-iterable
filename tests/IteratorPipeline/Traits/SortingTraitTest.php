<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Traits\SortingTrait
 */
class SortingTraitTest extends TestCase
{
    public function testSort()
    {
        $values = ['one' => 'img1.png', 'ten' => 'img10.png', 'twelve' => 'img12.png', 'two' => 'img2.png'];
        $pipeline = new Pipeline($values);

        $ret = $pipeline->sort(\SORT_NATURAL);
        $this->assertSame($pipeline, $ret);

        $expected = ['one' => 'img1.png', 'two' => 'img2.png', 'ten' => 'img10.png', 'twelve' => 'img12.png'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testSortNoKeys()
    {
        $pipeline = new Pipeline(['yy', 'z', 'xxxx', 'www']);

        $ret = $pipeline->sort(\SORT_STRING, false);
        $this->assertSame($pipeline, $ret);

        $expected = ['www', 'xxxx', 'yy', 'z'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testSortCallback()
    {
        $pipeline = new Pipeline(['yy', 'z', 'xxxx', 'www']);

        $ret = $pipeline->sort(function(string $a, string $b): int {
            return strlen($a) <=> strlen($b);
        }, false);

        $this->assertSame($pipeline, $ret);

        $expected = ['z', 'yy', 'www', 'xxxx'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testSortKeys()
    {
        $values = ['India' => 'two', 'Zulu' => 'four', 'Papa' => 'three', 'Bravo' => 'one'];
        $pipeline = new Pipeline($values);

        $ret = $pipeline->sortKeys(\SORT_STRING);
        $this->assertSame($pipeline, $ret);

        $expected = ['Bravo' => 'one', 'India' => 'two', 'Papa' => 'three', 'Zulu' => 'four'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testSortKeysCallback()
    {
        $values = ['India' => 'two', 'Zulu' => 'four', 'Papa' => 'three', 'Bravo' => 'one'];
        $pipeline = new Pipeline($values);

        $ret = $pipeline->sortKeys(function(string $a, string $b): int {
            return (strlen($a) <=> strlen($b)) ?: ($a <=> $b);
        });

        $this->assertSame($pipeline, $ret);

        $expected = ['Papa' => 'three', 'Zulu' => 'four', 'Bravo' => 'one', 'India' => 'two'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testReverse()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'quatro']);

        $ret = $pipeline->reverse();
        $this->assertSame($pipeline, $ret);

        $expected = ['four' => 'quatro', 'three' => 'tres', 'two' => 'dos', 'one' => 'uno'];
        $this->assertEquals($expected, $pipeline->toArray());
    }
}
