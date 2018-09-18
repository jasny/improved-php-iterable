<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Aggregator that produces the minimal/maximal element according to a given comparator.
 */
class MaxAggregator extends AbstractAggregator
{
    /**
     * @var callable
     */
    protected $compare;

    /**
     * MaxAggregator constructor.
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
     * Calculate max using basic logic.
     *
     * @return mixed
     */
    protected function simple()
    {
        $max = null;

        foreach ($this->iterator as $value) {
            $max = (!isset($max) || $value > $max) ? $value : $max;
        }

        return $max;
    }

    /**
     * Calculate max using custom logic.
     *
     * @return mixed
     */
    protected function custom()
    {
        $first = true;
        $min = null;

        foreach ($this->iterator as $value) {
            $min = ($first || call_user_func($this->compare, $min, $value) < 0) ? $value : $min;
            $first = false;
        }

        return $min;
    }
}
