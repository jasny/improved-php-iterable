<?php declare(strict_types=1);

namespace Improved\IteratorPipeline\Traits;

use Improved as i;

/**
 * Type handling methods for iterator pipeline.
 */
trait TypeHandlingTrait
{
    /**
     * Define the next step via a callback that returns an array or Traversable object.
     *
     * @param callable $callback
     * @param mixed    ...$args
     * @return static
     */
    abstract public function then(callable $callback, ...$args);


    /**
     * Validate that a value has a specific type.
     * @deprecated
     *
     * @param string|string[]        $type
     * @param string|\Throwable|null $error
     * @return static
     */
    public function expectType($type, $error = null)
    {
        return $this->then(i\iterable_expect_type, $type, $error);
    }

    /**
     * Validate that a value has a specific type.
     *
     * @param string|string[] $type
     * @param \Throwable|null $throwable
     * @return static
     */
    public function typeCheck($type, ?\Throwable $throwable = null)
    {
        return $this->then(i\iterable_type_check, $type, $throwable);
    }

    /**
     * Cast a value to the specific type or throw an error.
     *
     * @param string          $type
     * @param \Throwable|null $throwable
     * @return static
     */
    public function typeCast(string $type, ?\Throwable $throwable = null)
    {
        return $this->then(i\iterable_type_cast, $type, $throwable);
    }
}
