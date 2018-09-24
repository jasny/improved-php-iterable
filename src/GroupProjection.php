<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Projection;

use Jasny\IteratorPipeline\Iterator\CombineIterator;

/**
 * Group elements of an iterator.
 */
class GroupProjection implements \IteratorAggregate
{
    /**
     * @var iterable
     */
    protected $input;

    /**
     * @var callable
     */
    protected $grouping;


    /**
     * AbstractIterator constructor.
     *
     * @param iterable $input
     * @param callable $grouping
     */
    public function __construct(iterable $input, callable $grouping)
    {
        $this->input = $input;
        $this->grouping = $grouping;
    }

    /**
     * Get new iterator.
     *
     * @return CombineIterator
     */
    public function getIterator(): \Traversable
    {
        $groups = [];
        $values = [];

        foreach ($this->input as $key => $value) {
            $group = call_user_func($this->grouping, $value, $key);

            $index = array_search($group, $groups, true);

            if ($index === false) {
                $index = array_push($groups, $group) - 1;
            }

            $values[$index][] = $value;
        }

        return new CombineIterator($groups, $values);
    }
}
