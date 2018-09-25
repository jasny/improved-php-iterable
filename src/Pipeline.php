<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline;

use Jasny\IteratorPipeline\Traits\FilteringTrait;
use Jasny\IteratorPipeline\Traits\MappingTrait;
use Jasny\IteratorPipeline\Traits\SortingTrait;

use function Jasny\iterable_to_array;
use function Jasny\iterable_to_iterator;

/**
 * Functional-style operations, such as map-reduce transformations on arrays and iterators.
 * A pipeline uses Generators, meaning it can be used only once.
 */
class Pipeline implements \IteratorAggregate
{
    use MappingTrait;
    use FilteringTrait;
    use SortingTrait;

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
     * Set the next step of the pipeline.
     *
     * @param iterable
     * @return $this
     */
    protected function step(iterable $iterable): self
    {
        $this->iterable = $iterable;

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
        return iterable_to_array($this->iterable);
    }


    /**
     * Factory method
     *
     * @param iterable $iterable
     * @return static
     */
    public static function with(iterable $iterable): self
    {
        return new static($iterable);
    }
}
