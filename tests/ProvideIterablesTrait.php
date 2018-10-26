<?php declare(strict_types=1);

namespace Improved\Tests;

trait ProvideIterablesTrait
{
    protected function generateAssoc(iterable $values): \Generator
    {
        foreach ($values as $key => $value) {
            yield $key => $value;
        }
    }

    protected function generateTricky(iterable $values): \Generator
    {
        foreach ($values as $value) {
            yield [] => $value;
        }
    }

    public function provideIterables(array $values, $tricky = false, $fixedArray = true)
    {
        $tests = [
            [$values, $values],
            [new \ArrayIterator($values), $values],
            [new \ArrayObject($values), $values],
            [$this->generateAssoc($values), $values]
        ];

        if ($fixedArray) {
            $tests[] = [\SplFixedArray::fromArray(array_values($values)), array_values($values)];
        }

        if ($tricky) {
            $tests[] = [$this->generateTricky($values), array_values($values)];
        }

        return $tests;
    }
}
