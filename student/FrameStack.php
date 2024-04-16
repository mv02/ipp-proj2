<?php

namespace IPP\Student;

class FrameStack
{
    /** @var Frame[] */
    private array $stack = [];
    /** @var int */
    private int $size = 0;

    /**
     * @param Frame $frame
     */
    public function push(Frame $frame): void
    {
        $this->stack[$this->size++] = $frame;
    }

    /**
     * @return Frame
     */
    public function top(): Frame
    {
        return $this->stack[$this->size - 1];
    }

    /**
     * @return Frame
     */
    public function pop(): Frame
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
