<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline;

use Jasny\IteratorPipeline\Traits\FilteringTrait;
use Jasny\IteratorPipeline\Traits\FindingTrait;
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
    use FindingTrait;

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
    public function then(callable $callback, ...$args)
    {
        $next = $callback($this->iterable, ...$args);

        if (!is_iterable($next)) {
            throw new \UnexpectedValueException("Expected an array or Traversable, %s returned");
        }

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
        return iterable_to_array($this->iterable);
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
}
