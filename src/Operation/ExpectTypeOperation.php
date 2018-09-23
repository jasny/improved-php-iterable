<?php

declare(strict_types=1);

namespace Jasny\IteratorProjection\Operation;

use function Jasny\expect_type;

/**
 * Check the type of each element of an iterator.
 */
class ExpectTypeOperation extends AbstractOperation
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
     * @param iterable        $input
     * @param string|string[] $type
     * @param string          $throwable
     * @param string          $message
     */
    public function __construct(
        iterable $input,
        $type,
        string $throwable = \UnexpectedValueException::class,
        string $message = null
    ) {
        expect_type($type, ['string', 'array'], "Expected type to be a string or string[], %s given");

        parent::__construct($input);

        $this->type = $type;
        $this->throwable = $throwable;
        $this->message = $message;
    }

    /**
     * Apply the operation to every element of the input array / iterator.
     *
     * @return \Generator
     */
    protected function apply(): \Traversable
    {
        foreach ($this->input as $key => $value) {
            expect_type($value, $this->type, $this->throwable, $this->message);

            yield $key => $value;
        }
    }
}
