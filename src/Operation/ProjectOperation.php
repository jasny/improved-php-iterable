<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

/**
 * Project each element of an iterator to an associated (or numeric) array.
 * Scalar elements are untouched.
 */
class ProjectOperation extends AbstractOperation
{
    /**
     * @var array
     */
    protected $mapping;

    /**
     * Class constructor
     *
     * @param iterable $input
     * @param array    $mapping
     */
    public function __construct(iterable $input, array $mapping)
    {
        parent::__construct($input);

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
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            if (is_array($value) || $value instanceof \ArrayAccess) {
                $value = $this->projectArray($value);
            } elseif (is_object($value) && !$value instanceof \DateTimeInterface) {
                $value = $this->projectObject($value);
            }

            yield $key => $value;
        }
    }

}