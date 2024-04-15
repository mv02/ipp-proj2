<?php

namespace IPP\Student;

class CallStack
{
    private array $stack = [];
    private int $size = 0;

    public function push(int $position): void
    {
        $this->stack[$this->size++] = $position;
    }

    public function top(): int
    {
        return $this->stack[$this->size - 1];
    }

    public function pop(): int
    {
        return $this->stack[--$this->size];
    }

    public function isEmpty(): bool
    {
        return $this->size == 0;
    }
}
