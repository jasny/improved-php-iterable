<?php

namespace Jasny\IteratorPipeline\Tests\Aggregator;

use Jasny\IteratorPipeline\Aggregator\FirstAggregator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\IteratorPipeline\Aggregator\AbstractAggregator
 * @covers \Jasny\IteratorPipeline\Aggregator\FirstAggregator
 */
class FirstAggregatorTest extends TestCase
{
    public function testAggregate()
    {
        $values = ['one', 'two', 'three'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new FirstAggregator($iterator);

        $result = $aggregator();

        $this->assertEquals('one', $result);
        $this->assertSame($iterator, $aggregator->getIterator());
    }

    public function testAggregateCallback()
    {
        $values = ['one', 'two', 'three'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new FirstAggregator($iterator, function($value) {
            return substr($value, 0, 1) === 't';
        });

        $result = $aggregator();

        $this->assertEquals('two', $result);
    }

    public function testAggregateKey()
    {
        $values = ['one' => 'uno', 'two' => 'dos', 'three' => 'tres'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new FirstAggregator($iterator, function($value, $key) {
            return substr($key, 0, 1) === 't';
        });

        $result = $aggregator();

        $this->assertEquals('dos', $result);
    }

    public function testAggregateNotFound()
    {
        $values = ['one', 'two', 'three'];
        $iterator = new \ArrayIterator($values);

        $aggregator = new FirstAggregator($iterator, function($value) {
            return false;
        });

        $result = $aggregator();

        $this->assertNull($result);
    }

    public function testAggregateEmpty()
    {
        $aggregator = new FirstAggregator(new \EmptyIterator());

        $result = $aggregator();

        $this->assertNull($result);
    }

}
