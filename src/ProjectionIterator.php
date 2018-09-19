<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Project each element of an iterator to an associated (or numeric) array.
 * Scalar elements are ignored.
 */
class ProjectionIterator extends \IteratorIterator
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * Class constructor
     *
     * @param \Traversable $iterator
     * @param array        $mapping
     */
    public function __construct(\Traversable $iterator, array $mapping)
    {
        parent::__construct($iterator);

        $this->mapping = $mapping;
    }


    /**
     * Project an array.
     *
     * @param array|\ArrayAccess $element
     * @return array
     */
    protected function projectArray($element): array
    {
        $projected = [];

        foreach ($this->mapping as $to => $from) {
            $projected[$to] = $element[$from] ?? null;
        }

        return $projected;
    }

    /**
     * Project an object.
     *
     * @param object $element
     * @return array
     */
    protected function projectObject($element): array
    {
        $projected = [];

        foreach ($this->mapping as $to => $from) {
            $projected[$to] = $element->$from ?? null;
        }

        return $projected;
    }

    /**
     * Project a single element.
     *
     * @param mixed $element
     * @return mixed
     */
    protected function project($element)
    {
        if (is_array($element) || $element instanceof \ArrayAccess) {
            return $this->projectArray($element);
        }

        if (is_object($element) && !$element instanceof \DateTimeInterface) {
            return $this->projectObject($element);
        }

        return $element;
    }

    /**
     * Get the current element
     *
     * @return mixed
     */
    public function current()
    {
        return $this->project(parent::current());
    }
}