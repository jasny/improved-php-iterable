<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Group elements of an iterator.
 */
class GroupIteratorAggregate implements \IteratorAggregate
{
    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @var callable
     */
    protected $grouping;


    /**
     * AbstractIterator constructor.
     *
     * @param \Iterator $iterator
     * @param callable  $grouping
     */
    public function __construct(\Iterator $iterator, callable $grouping)
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

        return new CombineIterator(new \ArrayIterator($groups), new \ArrayIterator($values));
    }
}
