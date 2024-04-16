<?php

namespace IPP\Student;

class CallStack
{
    /** @var int[] */
    private array $stack = [];
    /** @var int */
    private int $size = 0;

    /**
     * @param int $position
     */
    public function push(int $position): void
    {
        $this->stack[$this->size++] = $position;
    }

    /**
     * @return int
     */
    public function top(): int
    {
        return $this->stack[$this->size - 1];
    }

    /**
     * @return int
     */
    public function pop(): int
    {
        return $this->stack[--$this->size];
    }

    /**
     * @return bool true if the stack is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return $this->size == 0;
    }
}
