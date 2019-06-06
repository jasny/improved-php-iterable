<?php declare(strict_types=1);

namespace Improved\Tests\IteratorPipeline\Traits;

use function Improved\iterable_to_array;
use Improved\IteratorPipeline\Pipeline;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Improved\IteratorPipeline\Traits\MappingTrait
 */
class MappingTraitTest extends TestCase
{
    public function testMap()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        $ret = $pipeline->map(function($value, $key) {
            return "$key:$value";
        });

        $this->assertSame($pipeline, $ret);

        $expected = ['one' => 'one:uno', 'two' => 'two:dos', 'three' => 'three:tres'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testMapKeys()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        $ret = $pipeline->mapKeys(function($value, $key) {
            return "$key:$value";
        });

        $this->assertSame($pipeline, $ret);

        $expected = ['one:uno' => 'uno', 'two:dos' => 'dos', 'three:tres' => 'tres'];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testApply()
    {
        $objects = [
            'client' => (object)['name' => 'John'],
            'partner' => (object)['name' => 'Jane'],
            'employee' => (object)['name' => 'Jack']
        ];

        $pipeline = new Pipeline($objects);

        $ret = $pipeline->apply(function($value, $key) {
            $value->role = $key;
        });

        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertSame($objects, $result);

        $expected = [
            'client' => (object)['name' => 'John', 'role' => 'client'],
            'partner' => (object)['name' => 'Jane', 'role' => 'partner'],
            'employee' => (object)['name' => 'Jack', 'role' => 'employee']
        ];
        $this->assertEquals($expected, $result);
    }


    public function testChunk()
    {
        $pipeline = new Pipeline(array_fill(0, 45, null));

        $lengths = [];
        $ret = $pipeline->chunk(10);

        foreach ($ret as $chunk) {
            $lengths[] = count(iterable_to_array($chunk));
        }

        $this->assertSame($pipeline, $ret);

        $this->assertEquals([10, 10, 10, 10, 5], $lengths);
    }

    public function testGroup()
    {
        $pipeline = new Pipeline(['apple', 'pear', 'berry', 'apricot', 'banana', 'cherry']);

        $ret = $pipeline->group(function($value) {
            return $value[0];
        });

        $this->assertSame($pipeline, $ret);

        $expected = ['a' => ['apple', 'apricot'], 'p' => ['pear'], 'b' => ['berry', 'banana'], 'c' => ['cherry']];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testFlatten()
    {
        $pipeline = new Pipeline([['one', 'two'], 'three', [], ['four']]);

        $ret = $pipeline->flatten();
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();

        $this->assertEquals(['one', 'two', 'three', 'four'], $result);
    }

    public function testFlattenPreserveKeys()
    {
        $values = ['a' => ['I' => 'one', 'II' =>'two'], 'b' => 'three', 'c' => [], 'd' => ['****' => 'four']];
        $pipeline = new Pipeline($values);

        $ret = $pipeline->flatten(true);
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();

        $this->assertEquals(['I' => 'one', 'II' => 'two', 'b' => 'three', '****' => 'four'], $result);
    }

    public function testUnwind()
    {
        $values = [
            ['ref' => 'a', 'numbers' => ['I' => 'one', 'II' =>'two']],
            ['ref' => 'b', 'numbers' =>  'three'],
            ['ref' => 'c', 'numbers' => []]
        ];
        $pipeline = new Pipeline($values);

        $ret = $pipeline->unwind('numbers', 'nrkey');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();

        $expected = [
            ['ref' => 'a', 'numbers' => 'one', 'nrkey' => 'I'],
            ['ref' => 'a', 'numbers' => 'two', 'nrkey' => 'II'],
            ['ref' => 'b', 'numbers' => 'three'],
            ['ref' => 'c', 'numbers' => null, 'nrkey' => null],
        ];

        $this->assertEquals($expected, $result);
    }


    public function testFill()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        $ret = $pipeline->fill(42);

        $this->assertSame($pipeline, $ret);

        $expected = ['one' => 42, 'two' => 42, 'three' => 42];
        $this->assertEquals($expected, $pipeline->toArray());
    }

    public function testColumn()
    {
        $rows = [
            ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' =>'four'],
            ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'quatro'],
            ['I' => 'één', 'II' => 'twee', 'IV' => 'vier'],
        ];

        $pipeline = new Pipeline($rows);

        $ret = $pipeline->column('III');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals(['three', 'tres', null], $result);
    }

    public function testColumnPairs()
    {
        $rows = [
            ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' =>'four'],
            ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'quatro'],
            ['I' => 'één', 'II' => 'twee', 'IV' => 'vier'],
        ];

        $pipeline = new Pipeline($rows);

        $ret = $pipeline->column('III', 'II');
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();
        $this->assertEquals(['two' => 'three', 'dos' => 'tres', 'twee' => null], $result);
    }

    public function testProject()
    {
        $rows = [
            [1 => 'one', 2 => 'two', 3 => 'three', 4 =>'four', 5 => 'five'],
            [1 => 'uno', 3 => 'tres', 2 => 'dos', 4 => 'quatro'],
            [1 => 'één', 4 => 'vier'],
        ];

        $pipeline = new Pipeline($rows);

        $ret = $pipeline->project(['I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4]);
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();

        $expected = [
            ['I' => 'one', 'II' => 'two', 'III' => 'three', 'IV' =>'four'],
            ['I' => 'uno', 'II' => 'dos', 'III' => 'tres', 'IV' => 'quatro'],
            ['I' => 'één', 'II' => null, 'III' => null, 'IV' => 'vier'],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testReshape()
    {
        $rows = [
            (object)['one' => 'uno', 'two' => 'dos', 'three' => 'tres', 'four' => 'cuatro', 'five' => 'cinco'],
            (object)['three' => 'san', 'one' => 'yi', 'two' => 'er', 'five' => 'wu', 'four' => 'si'],
            (object)['two' => 'twee', 'five' => 'vijf', 'three' => 'drie', 'four' => 'vier']
        ];

        $pipeline = new Pipeline($rows);

        $ret = $pipeline->reshape(['one' => true, 'two' => false, 'three' => 'III', 'four' => 0]);
        $this->assertSame($pipeline, $ret);

        $result = $pipeline->toArray();

        $expected = [
            (object)['one' => 'uno', 'five' => 'cinco', 'III' => 'tres', 0 => 'cuatro'],
            (object)['one' => 'yi', 'five' => 'wu', 'III' => 'san', 0 => 'si'],
            (object)['five' => 'vijf', 'III' => 'drie', 0 => 'vier']
        ];

        $this->assertSame($rows, $result); // Still the same objects
        $this->assertEquals($expected, $result);
    }


    public function testValues()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        $ret = $pipeline->values();
        $this->assertSame($pipeline, $ret);

        $this->assertEquals(['uno', 'dos', 'tres'], $pipeline->toArray());
    }

    public function testKeys()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        $ret = $pipeline->keys();
        $this->assertSame($pipeline, $ret);

        $this->assertEquals(['one', 'two', 'three'], $pipeline->toArray());
    }


    public function iterableProvider()
    {
        $generator = function($values) {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };

        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];

        $tests = [
            [$values, $values],
            [new \ArrayIterator($values), $values],
            [new \ArrayObject($values), $values],
            [$generator($values), $values]
        ];

        return $tests;
    }

    /**
     * @dataProvider iterableProvider
     */
    public function testSetKeys($keys)
    {
        $pipeline = new Pipeline(['een', 42 => 'twee']);

        $ret = $pipeline->setKeys($keys);
        $this->assertSame($pipeline, $ret);

        $this->assertEquals(['uno' => 'een', 'dos' => 'twee', 'tres' => null], $pipeline->toArray());
    }

    public function testFlip()
    {
        $pipeline = new Pipeline(['one' => 'uno', 'two' => 'dos', 'three' => 'tres']);

        $ret = $pipeline->flip();
        $this->assertSame($pipeline, $ret);

        $this->assertEquals(['uno' => 'one', 'dos' => 'two', 'tres' => 'three'], $pipeline->toArray());
    }
}
