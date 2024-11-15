<?php

declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved\IteratorPipeline\Pipeline;
use Throwable;

/**
 * Type handling methods for iterator pipeline.
 */
trait TypeHandlingTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     */
    abstract public function then(callable $callback, mixed ...$args): static;


    /**
     * Validate that a value has a specific type.
     *
     * @param string|string[] $type
     * @param Throwable|string|null $error
     * @return static&Pipeline
     *
     * @deprecated Use typeCheck() instead.
     */
    public function expectType(array|string $type, Throwable|string|null $error = null): static
    {
        return $this->then("Improved\iterable_expect_type", $type, $error);
    }

    /**
     * Validate that a value has a specific type.
     *
     * @param string|string[] $type
     * @param Throwable|null $throwable
     * @return static&Pipeline
     */
    public function typeCheck(array|string $type, ?Throwable $throwable = null): static
    {
        return $this->then("Improved\iterable_type_check", $type, $throwable);
    }

    /**
     * Cast a value to the specific type or throw an error.
     *
     * @param string         $type
     * @param Throwable|null $throwable
     * @return static&Pipeline
     */
    public function typeCast(string $type, ?Throwable $throwable = null): static
    {
        return $this->then("Improved\iterable_type_cast", $type, $throwable);
    }
}
