<?php

declare(strict_types=1);

namespace Jasny\Iterator;

/**
 * Turn any Traversable into an Iterator.
 */
trait TraversableIteratorTrait
{
    /**
     * Turn any Traversable into an Iterator.
     *
     * @param \Traversable $traversable
     * @return \Iterator
     */
    public function traverableToIterator(\Traversable $traversable): \Iterator
    {
        switch (true) {
            case $traversable instanceof \Iterator:
                return $traversable;
            case $traversable instanceof \IteratorAggregate:
                return $traversable->getIterator();
            default:
                return new \IteratorIterator($traversable);
        }
    }
}
