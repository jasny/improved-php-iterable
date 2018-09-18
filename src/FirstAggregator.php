<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Get the first element or the first element that matches a condition.
 * Returns null if no element is found.
 */
class FirstAggregator extends AbstractAggregator
{
    /**
     * @var callable
     */
    protected $matcher;

    /**
     * FindAggregator constructor.
     *
     * @param \Traversable $iterator
     * @param callable $matcher
     */
    public function __construct(\Traversable $iterator, callable $matcher = null)
    {
        parent::__construct($iterator);

        $this->matcher = $matcher ?? [$this, 'nop'];
    }

    /**
     * Invoke aggregator
     *
     * @return mixed
     */
    public function __invoke()
    {
        foreach ($this->iterator as $key => $value) {
            $found = (bool)call_user_func($this->matcher, $value, $key);

            if ($found) {
                return $value;
            }
        }

        return null;
    }


    /**
     * No operation.
     *
     * @return bool
     */
    protected function nop(): bool
    {
        return true;
    }
}
