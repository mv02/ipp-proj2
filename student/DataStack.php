<?php

namespace IPP\Student;

class DataStack
{
    private array $stack = [];
    private int $size = 0;

    public function push(Symbol $symbol): void
    {
        $this->stack[$this->size++] = $symbol;
    }

    public function top(): Symbol
    {
        return $this->stack[$this->size - 1];
    }

    public function pop(): Symbol
    {
        return $this->stack[--$this->size];
    }

    public function isEmpty(): bool
    {
        return $this->size == 0;
    }
}
