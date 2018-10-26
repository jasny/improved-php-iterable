<?php declare(strict_types=1);

namespace Improved\IteratorPipeline\PipelineBuilder;

/**
 * A stub for a step in the pipeline builder.
 * @internal
 */
class Stub
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Class constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the stub.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Invoke stub
     *
     * @param iterable $iterable
     * @return iterable
     */
    public function __invoke(iterable $iterable)
    {
        return $iterable;
    }
}
