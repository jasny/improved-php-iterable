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
        if ($traversable instanceof \IteratorAggregate) {
            $traversable = $traversable->getIterator();
        }

        if (!$traversable instanceof \Iterator) {
            $traversable = new \IteratorIterator($traversable); // @codeCoverageIgnore
        }

        return $traversable;
    }
}
