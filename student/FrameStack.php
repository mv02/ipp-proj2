<?php

namespace IPP\Student;

class FrameStack
{
    private array $stack = [];
    private int $size = 0;

    public function push(Frame $frame): void
    {
        $this->stack[$this->size++] = $frame;
    }

    public function top(): Frame
    {
        return $this->stack[$this->size - 1];
    }

    public function pop(): Frame
    {
        return $this->stack[--$this->size];
    }

    public function isEmpty(): bool
    {
        return $this->size == 0;
    }
}
