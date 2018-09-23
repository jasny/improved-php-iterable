<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Operation;

/**
 * Filter elements using callback
 */
class FilterOperation extends AbstractOperation
{
    /**
     * @var callable
     */
    protected $predicate;


    /**
     * Constructor.
     *
     * @param iterable $input
     * @param callable $predicate
     */
    public function __construct(iterable $input, callable $predicate)
    {
        parent::__construct($input);

        $this->predicate = $predicate;
    }


    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            if ((bool)call_user_func($this->predicate, $value, $key)) {
                yield $key => $value;
            }
        }
    }
}
