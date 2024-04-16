<?php

namespace IPP\Student;

class DataStack
{
    /** @var Symbol[] */
    private array $stack = [];
    /** @var int */
    private int $size = 0;

    /**
     * @param Symbol $symbol
     */
    public function push(Symbol $symbol): void
    {
        $this->stack[$this->size++] = $symbol;
    }

    /**
     * @return Symbol
     */
    public function top(): Symbol
    {
        return $this->stack[$this->size - 1];
    }

    /**
     * @return Symbol
     */
    public function pop(): Symbol
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
