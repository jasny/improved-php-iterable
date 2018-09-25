<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline;

use Jasny\IteratorPipeline\Traits\MappingTrait;

use function Jasny\iterable_to_array;
use function Jasny\iterable_to_iterator;

/**
 * Functional-style operations, such as map-reduce transformations on arrays and iterators.
 */
class Pipeline implements \IteratorAggregate
{
    use MappingTrait;

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
}
