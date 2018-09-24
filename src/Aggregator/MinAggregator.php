<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Aggregator that produces the minimal element according to a given comparator.
 */
class MinAggregator extends AbstractAggregator
{
    /**
     * @var callable
     */
    protected $compare;

    /**
     * MinAggregator constructor.
     *
     * @param \Traversable $iterator
     * @param callable|null $compare
     */
    public function __construct(\Traversable $iterator, callable $compare = null)
    {
        parent::__construct($iterator);

        $this->compare = $compare;
    }


    /**
     * Invoke the aggregator.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return isset($this->compare) ? $this->custom() : $this->simple();
    }

    /**
     * Calculate min using basic logic.
     *
     * @return mixed
     */
    protected function simple()
    {
        $min = null;

        foreach ($this->iterator as $value) {
            $min = (!isset($min) || $value < $min) ? $value : $min;
        }

        return $min;
    }

    /**
     * Calculate min using custom logic.
     *
     * @return mixed
     */
    protected function custom()
    {
        $first = true;
        $min = null;

        foreach ($this->iterator as $value) {
            $min = ($first || call_user_func($this->compare, $min, $value) > 0) ? $value : $min;
            $first = false;
        }

        return $min;
    }
}
