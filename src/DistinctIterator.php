<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Filter to get only unique items.
 */
class DistinctIterator extends \FilterIterator
{
    /**
     * @var callable
     */
    protected $serialize;

    /**
     * @var int
     */
    protected $counter = 0;

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
     * @param \Iterator $iterator
     * @param callable  $serialize  Callable function to serialize the value
     */
    public function __construct(\Iterator $iterator, callable $serialize = null)
    {
        parent::__construct($iterator);

        $this->serialize = $serialize ?? [$this, 'nop'];
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        $entry = call_user_func($this->serialize, parent::current());

        if (!is_string($entry) && $this->useFastMap) {
            $this->disableFastMap();
        }

        return $this->addEntry($entry);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->counter = 0;
        $this->map = [];
        $this->useFastMap = true;

        parent::rewind();
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
            $this->map[$entry] = $this->counter++;
        } else {
            $this->map[] = $entry;
        }

        return true;
    }


    /**
     * No operation
     *
     * @param mixed $item
     * @return mixed
     */
    protected function nop($item)
    {
        return $item;
    }
}
