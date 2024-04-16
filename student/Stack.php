<?php

namespace IPP\Student;

/**
 * @template T of mixed
 */
class Stack
{
    /** @var T[] */
    private array $stack = [];
    /** @var int */
    private int $size = 0;

    /**
     * @param T $item
     */
    public function push($item): void
    {
        $this->stack[$this->size++] = $item;
    }

    /**
     * @return T
     */
    public function top(): mixed
    {
        return $this->stack[$this->size - 1];
    }

    /**
     * @return T
     */
    public function pop(): mixed
    {
        return $this->stack[--$this->size];
    }

    /**
     * @return bool true if the stack is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return $this->size === 0;
    }
}
