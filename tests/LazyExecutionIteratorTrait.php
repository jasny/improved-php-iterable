<?php declare(strict_types=1);

namespace Improved\Tests;

use PHPUnit\Framework\MockObject\MockObject;

trait LazyExecutionIteratorTrait
{
    /**
     * Returns a test double for the specified class.
     *
     * @param string|string[] $originalClassName
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    abstract protected function createMock($originalClassName): MockObject;

    /**
     * Create an Iterator mock that fails when traversing.
     *
     * @return \Iterator|MockObject
     */
    protected function createLazyExecutionIterator()
    {
        $mock = $this->createMock(\Iterator::class);

        $mock->expects($this->any())->method('valid')->willReturn(true);
        $mock->expects($this->any())->method('rewind');
        $mock->expects($this->never())->method('key');
        $mock->expects($this->never())->method('current');

        return $mock;
    }
}