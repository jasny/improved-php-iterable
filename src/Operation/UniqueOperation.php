<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Filter to get only unique elements.
 */
class UniqueOperation extends AbstractOperation
{
    /**
     * @var callable|null
     */
    protected $serialize;

    /**
     * @var array
     */
    protected $map = [];

    /**
     * @var bool  Use keys in map
     */
    protected $useFastMap = true;


    /**
     * Constructor.
     *
     * @param iterable      $input
     * @param callable|null $serialize  Callable function to serialize the value
     */
    public function __construct(iterable $input, callable $serialize = null)
    {
        parent::__construct($input);

        $this->serialize = $serialize;
    }


    /**
     * Check whether the current element is acceptable.
     *
     * @param mixed $value
     * @param mixed $key
     * @return bool
     */
    protected function accept($value, $key): bool
    {
        $entry = isset($this->serialize)
            ? call_user_func($this->serialize, $value, $key)
            : $value;

        if (!is_string($entry) && $this->useFastMap) {
            $this->disableFastMap();
        }

        return $this->addEntry($entry);
    }

    /**
     * Disable fast map, because entries aren't a string.
     *
     * @return void
     */
    protected function disableFastMap(): void
    {
        $this->map = array_flip($this->map);
        $this->useFastMap = false;
    }

    /**
     * Add an entry to the map.
     * Returns TRUE if entry has been added and FALSE if it was already in the map.
     *
     * @param mixed $entry
     * @return bool
     */
    protected function addEntry($entry): bool
    {
        $exists = $this->useFastMap ? isset($this->map[(string)$entry]) : in_array($entry, $this->map, true);

        if ($exists) {
            return false;
        }

        if ($this->useFastMap) {
            $this->map[$entry] = count($this->map);
        } else {
            $this->map[] = $entry;
        }

        return true;
    }


    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            if ($this->accept($value, $key)) {
                yield $key => $value;
            }
        }
    }
}
