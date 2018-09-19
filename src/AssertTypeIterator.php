<?php

declare(strict_types=1);

namespace Jasny\Iterator;

use function Jasny\expect_type;

/**
 * Assert the type of each element of an iterator.
 */
class AssertTypeIterator extends \IteratorIterator
{
    /**
     * @var string|string[]  Type(s)
     */
    protected $type;

    /**
     * @var string  Class name
     */
    protected $throwable;

    /**
     * @var string
     */
    protected $message;


    /**
     * Class constructor.
     *
     * @param \Traversable    $iterator
     * @param string|string[] $type
     * @param string          $throwable
     * @param string          $message
     */
    public function __construct(
        \Traversable $iterator,
        $type,
        string $throwable = \UnexpectedValueException::class,
        string $message = null
    ) {
        expect_type($type, ['string', 'array'], "Expected type to be a string or string[], %s given");

        parent::__construct($iterator);

        $this->type = $type;
        $this->throwable = $throwable;
        $this->message = $message;
    }

    /**
     * Assert the current value.
     */
    protected function assertCurrent(): void
    {
        if (!$this->valid()) {
            return;
        }

        expect_type($this->current(), $this->type, $this->throwable, $this->message);
    }

    /**
     * Move to next element and assert.
     *
     * @return void
     */
    public function next(): void
    {
        parent::next();

        $this->assertCurrent();
    }

    /**
     * Rewind to first element and assert.
     *
     * @return void
     */
    public function rewind()
    {
        parent::rewind();

        $this->assertCurrent();
    }
}
