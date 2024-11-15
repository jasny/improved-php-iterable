<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline;

use Improved as i;
use Iterator;
use IteratorAggregate;
use UnexpectedValueException;

/**
 * Functional-style operations, such as map-reduce transformations on arrays and iterators.
 * A pipeline uses Generators, meaning it can be used only once.
 *
 * @phpstan-consistent-constructor
 * @implements IteratorAggregate<mixed, mixed>
*/
class Pipeline implements IteratorAggregate
{
    use Traits\MappingTrait;
    use Traits\FilteringTrait;
    use Traits\SortingTrait;
    use Traits\TypeHandlingTrait;
    use Traits\FindingTrait;
    use Traits\AggregationTrait;

    /**
     * @var iterable<mixed, mixed>
     */
    protected iterable $iterable;

    /**
     * Pipeline constructor.
     *
     * @param iterable<mixed, mixed> $iterable
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
     * @return self
     */
    public function then(callable $callback, mixed ...$args): self
    {
        $this->iterable = i\type_check(
            $callback($this->iterable, ...$args),
            'iterable',
            new UnexpectedValueException("Expected step to return an array or Traversable, %s returned")
        );

        return $this->iterable instanceof Pipeline ? $this->iterable : $this;
    }


    /**
     * Get iterator.
     *
     * @return Iterator<mixed, mixed>
     */
    public function getIterator(): Iterator
    {
        return i\iterable_to_iterator($this->iterable);
    }

    /**
     * Get iterable as array.
     *
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return i\iterable_to_array($this->iterable, true);
    }

    /**
     * Traverse over the iterator, not capturing the values.
     * This is particularly useful after `apply()`.
     */
    public function walk(): void
    {
        i\iterable_walk($this->iterable);
    }


    /**
     * Factory method
     *
     * @param iterable<mixed, mixed> $iterable
     * @return static
     */
    final public static function with(iterable $iterable): static
    {
        return $iterable instanceof static ? $iterable : new static($iterable);
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
