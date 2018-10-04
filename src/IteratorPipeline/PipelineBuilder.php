<?php

declare(strict_types=1);

namespace Ipl\IteratorPipeline;

use Ipl as i;
use Ipl\Iterator\CombineIterator;
use Ipl\IteratorPipeline\Traits\FilteringTrait;
use Ipl\IteratorPipeline\Traits\MappingTrait;
use Ipl\IteratorPipeline\Traits\SortingTrait;

/**
 * The `PipelineBuilder` can be used to create a blueprint for pipelines.
 */
class PipelineBuilder
{
    use MappingTrait;
    use FilteringTrait;
    use SortingTrait;

    /**
     * @var array
     */
    protected $steps = [];


    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed    ...$args
     * @return static
     */
    public function then(callable $callback, ...$args): self
    {
        $copy = clone $this;

        if ($callback instanceof self) {
            $copy->steps = array_merge($this->steps, $callback->steps);
        } else {
            $copy->steps[] = [$callback, $args];
        }

        return $copy;
    }

    /**
     * Create a new pipeline
     *
     * @param iterable $iterable
     * @return Pipeline
     */
    public function with(iterable $iterable): Pipeline
    {
        $pipeline = new Pipeline($iterable);

        foreach ($this->steps as [$callback, $args]) {
            $pipeline->then($callback, ...$args);
        }

        return $pipeline;
    }

    /**
     * Invoke the builder.
     *
     * @param iterable $iterable
     * @return array
     */
    public function __invoke(iterable $iterable): array
    {
        return $this->with($iterable)->toArray();
    }


    /**
     * Use another iterator as keys and the current iterator as values.
     *
     * @param iterable $keys  Keys will be turned into an array.
     * @return static
     */
    public function setKeys(iterable $keys)
    {
        $combine = function ($values, $keys) {
            return new CombineIterator($keys, $values);
        };

        return $this->then($combine, i\iterable_to_array($keys));
    }
}
