<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Map all elements of an Iterator.
 */
class MapOperation extends AbstractOperation
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Constructor.
     *
     * @param iterable $input
     * @param callable $callable
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
            yield $key => call_user_func($this->callback, $value, $key);
        }
    }
}
