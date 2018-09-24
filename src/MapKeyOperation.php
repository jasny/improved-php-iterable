<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Operation;

/**
 * Map all keys of an Iterator.
 */
class MapKeyOperation extends AbstractOperation
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Constructor.
     *
     * @param iterable $input
     * @param callable $callback
     */
    public function __construct(iterable $input, callable $callback)
    {
        parent::__construct($input);

        $this->callback = $callback;
    }

    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            yield call_user_func($this->callback, $key, $value) => $value;
        }
    }
}
