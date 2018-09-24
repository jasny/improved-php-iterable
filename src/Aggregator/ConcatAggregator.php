<?php

declare(strict_types=1);

namespace Jasny\Aggregator;

/**
 * Concatenates the input elements, separated by the specified delimiter, in encounter order.
 */
class ConcatAggregator extends AbstractAggregator
{
    /**
     * @var string
     */
    protected $glue;

    /**
     * ConcatAggregator constructor.
     *
     * @param \Traversable $iterator
     * @param string       $glue
     */
    public function __construct(\Traversable $iterator, string $glue = '')
    {
        parent::__construct($iterator);

        $this->glue = $glue;
    }

    /**
     * Invoke the aggregator.
     *
     * @return string
     * @throws \UnexpectedValueException if not all values can be cast to a string
     */
    public function __invoke(): string
    {
        $string = "";

        foreach ($this->iterator as $item) {
            if (!is_scalar($item) && (!is_object($item) || !method_exists($item, '__toString'))) {
                $type = (is_object($item) ? get_class($item) . ' ' : '') . gettype($item);
                throw new \UnexpectedValueException("All elements should be usable as string, $type given");
            }

            $string .= $item . $this->glue;
        }

        return $this->glue === "" ? $string : substr($string, 0, -1 * strlen($this->glue));
    }
}
