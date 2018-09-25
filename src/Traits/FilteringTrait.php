<?php

declare(strict_types=1);

namespace Jasny\IteratorPipeline\Traits;

use Jasny\IteratorPipeline\Pipeline;
use function Jasny\iterable_filter;
use function Jasny\iterable_cleanup;
use function Jasny\iterable_unique;
use function Jasny\iterable_slice;
use function Jasny\iterable_expect_type;

/**
 * Filtering methods for iterator pipeline.
 */
trait FilteringTrait
{
    /**
     * @var iterable
     */
    protected $iterable;

    /**
     * Set the next step of the pipeline.
     *
     * @param iterable
     * @return $this
     */
    abstract protected function step(iterable $iterable): Pipeline;


    /**
     * Eliminate elements based on a criteria.
     *
     * @param callable $matcher
     * @return $this
     */
    public function filter(callable $matcher): Pipeline
    {
        return $this->step(iterable_filter($this->iterable, $matcher));
    }

    /**
     * Filter out `null` values from iteratable.
     *
     * @return $this
     */
    public function cleanup(): Pipeline
    {
        return $this->step(iterable_cleanup($this->iterable));
    }

    /**
     * Filter on unique elements.
     *
     * @param callable|null $grouper  If provided, filtering will be based on return value.
     * @return $this
     */
    public function unique(?callable $grouper = null): Pipeline
    {
        return $this->step(iterable_unique($this->iterable, $grouper));
    }

    /**
     * Filter our duplicate keys.
     * Unlike associative arrays, the keys of iterators don't have to be unique.
     *
     * @return $this
     */
    public function uniqueKeys(): Pipeline
    {
        return $this->step(iterable_unique($this->iterable, function($value, $key) {
            return $key;
        }));
    }

    /**
     * Get only the first elements of an iterator.
     *
     * @param int $size
     * @return $this
     */
    public function limit(int $size): Pipeline
    {
        return $this->step(iterable_slice($this->iterable, 0, $size));
    }

    /**
     * Get a limited subset of the elements using an offset.
     *
     * @param int      $offset
     * @param int|null $size    size limit
     * @return $this
     */
    public function slice(int $offset, ?int $size = null): Pipeline
    {
        return $this->step(iterable_slice($this->iterable, $offset, $size));
    }


    /**
     * Validate that a value has a specific type.
     * @see https://github.com/jasny/php-functions#expect_type
     *
     * @param string|string[] $type
     * @param string|null     $message
     * @return FilteringTrait
     * @throws \UnexpectedValueException
     */
    public function expectType($type, string $message = null)
    {
        return $this->step(iterable_expect_type($this->iterable, $type, \UnexpectedValueException::class, $message));
    }
}
