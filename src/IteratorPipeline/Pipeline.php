<?php

declare(strict_types=1);

namespace Ipl\IteratorPipeline;

use Ipl\IteratorPipeline\Traits\AggregationTrait;
use Ipl\IteratorPipeline\Traits\FilteringTrait;
use Ipl\IteratorPipeline\Traits\FindingTrait;
use Ipl\IteratorPipeline\Traits\MappingTrait;
use Ipl\IteratorPipeline\Traits\SortingTrait;

use function Ipl\iterable_to_array;
use function Ipl\iterable_to_iterator;
use function Jasny\expect_type;

/**
 * Functional-style operations, such as map-reduce transformations on arrays and iterators.
 * A pipeline uses Generators, meaning it can be used only once.
 */
class Pipeline implements \IteratorAggregate
{
    use MappingTrait;
    use FilteringTrait;
    use SortingTrait;
    use FindingTrait;
    use AggregationTrait;

    /**
     * @var iterable
     */
    protected $iterable;

    /**
     * Pipeline constructor.
     *
     * @param iterable $iterable
     */
    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed    ...$args
     * @return $this
     */
    public function then(callable $callback, ...$args): self
    {
        $next = $callback($this->iterable, ...$args);

        expect_type($next, 'iterable', \UnexpectedValueException::class,
            "Expected an array or Traversable, %s returned");

        $this->iterable = $next;

        return $this;
    }


    /**
     * Get iterator.
     *
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return iterable_to_iterator($this->iterable);
    }

    /**
     * Get iterable as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return iterable_to_array($this->iterable, true);
    }


    /**
     * Factory method
     *
     * @param iterable $iterable
     * @return static
     */
    final public static function with(iterable $iterable): self
    {
        return new static($iterable);
    }

    /**
     * Factory method for PipelineBuilder
     *
     * @return PipelineBuilder
     */
    public static function build(): PipelineBuilder
    {
        return new PipelineBuilder();
    }
}
