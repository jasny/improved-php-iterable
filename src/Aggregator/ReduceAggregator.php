<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Reduce all elements to a single value using a callback.
 */
class ReduceAggregator extends AbstractAggregator
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var mixed
     */
    protected $initial;


    /**
     * FindAggregator constructor.
     *
     * @param \Traversable $iterator
     * @param callable     $callback
     * @param mixed        $initial
     */
    public function __construct(\Traversable $iterator, callable $callback, $initial = null)
    {
        parent::__construct($iterator);

        $this->callback = $callback;
        $this->initial = $initial;
    }

    /**
     * Invoke aggregator
     *
     * @return mixed
     */
    public function __invoke()
    {
        $agg = $this->initial;

        foreach ($this->iterator as $key => $value) {
            $agg = call_user_func($this->callback, $agg, $value);
        }

        return $agg;
    }
}
