<?php /** @noinspection PhpInternalEntityUsedInspection */ declare(strict_types=1);


namespace Improved\IteratorPipeline;

use Improved as i;
use Improved\Iterator\CombineIterator;
use Improved\IteratorPipeline\PipelineBuilder\Stub;
use Improved\IteratorPipeline\Traits\FilteringTrait;
use Improved\IteratorPipeline\Traits\MappingTrait;
use Improved\IteratorPipeline\Traits\SortingTrait;

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
     * Add a stub, which does nothing but can be replaced later.
     *
     * @param string $name
     * @return static
     * @throw \BadMethodCallException if stub already exists
     */
    public function stub(string $name): self
    {
        $hasStub = i\iterable_has_any($this->steps, function ($step) use ($name) {
            return $step[0] instanceof Stub && $step[0]->getName() === $name;
        });

        if ($hasStub) {
            throw new \BadMethodCallException("Pipeline builder already has '$name' stub");
        }

        return $this->then(new Stub($name));
    }

    /**
     * Get a pipeline builder where a stub is replaced.
     *
     * @param string   $name
     * @param callable $callable
     * @param mixed    ...$args
     * @return static
     */
    public function unstub(string $name, callable $callable, ...$args): self
    {
        $index = i\iterable_find_key($this->steps, function ($step) use ($name) {
            return $step[0] instanceof Stub && $step[0]->getName() === $name;
        });

        if ($index === null) {
            throw new \BadMethodCallException("Pipeline builder doesn't have '$name' stub");
        }

        $clone = clone $this;
        $clone->steps[$index] = [$callable, $args];

        return $clone;
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
            $pipeline = $pipeline->then($callback, ...$args);
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
