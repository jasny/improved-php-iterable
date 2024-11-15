<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline\PipelineBuilder;

/**
 * A stub for a step in the pipeline builder.
 * @internal
 */
class Stub
{
    protected string $name;

    /**
     * Class constructor.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the stub.
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Invoke stub
     *
     * @template TKey
     * @template TValue
     * @param iterable<TKey, TValue> $iterable
     * @return iterable<TKey, TValue>
     */
    public function __invoke(iterable $iterable): iterable
    {
        return $iterable;
    }
}
