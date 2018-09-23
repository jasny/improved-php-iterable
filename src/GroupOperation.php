<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Group elements of an iterator.
 */
class GroupOperation implements \IteratorAggregate
{
    /**
     * @var \Traversable
     */
    protected $iterator;

    /**
     * @var callable
     */
    protected $grouping;


    /**
     * AbstractIterator constructor.
     *
     * @param \Traversable $iterator
     * @param callable     $grouping
     */
    public function __construct(\Traversable $iterator, callable $grouping)
    {
        $this->iterator = $iterator;
        $this->grouping = $grouping;
    }

    /**
     * Group all elements of the iterator.
     *
     * @return array [keys, values]
     */
    protected function group(): array
    {
        $groups = [];
        $values = [];

        foreach ($this->iterator as $key => $value) {
            $group = call_user_func($this->grouping, $value, $key);

            $index = array_search($group, $groups, true);

            if ($index === false) {
                $index = array_push($groups, $group) - 1;
            }

            $values[$index][] = $value;
        }

        return [$groups, $values];
    }

    /**
     * Get the iterator with sorted values
     *
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        list($groups, $values) = $this->group();

        return new CombineOperation(new \ArrayIterator($groups), new \ArrayIterator($values));
    }
}
