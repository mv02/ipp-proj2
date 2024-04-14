<?php

namespace IPP\Student;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exception\FrameAccessError;
use IPP\Student\Exception\SemanticError;
use IPP\Student\Exception\VariableAccessError;

class Environment
{
    private array $labels = [];
    private Frame $gf;
    private ?Frame $tf = null;
    private FrameStack $frameStack;

    public function __construct(private OutputWriter $writer, private InputReader $reader)
    {
        $this->gf = new Frame();
        $this->frameStack = new FrameStack();
    }

    private function frame(string $type): ?Frame
    {
        switch ($type) {
            case "GF":
                return $this->gf;
            case "TF":
                return $this->tf;
            case "LF":
                return $this->frameStack->top();
            default:
                return null;
        }
    }

    public function createFrame(): void
    {
        $this->tf = new Frame();
    }

    public function pushFrame(): void
    {
        if ($this->tf == null) {
            throw new FrameAccessError("Temporary frame is undefined");
        }

        $this->frameStack->push($this->tf);
        $this->tf = null;
    }

    public function popFrame(): void
    {
        if ($this->frameStack->isEmpty()) {
            throw new FrameAccessError("Frame stack is empty");
        }

        $this->tf = $this->frameStack->pop();
    }

    public function define(string $name, string $frameType): void
    {
        $frame = $this->frame($frameType);

        if ($frame == null) {
            throw new FrameAccessError();
        }

        if ($frame->get($name) != null) {
            throw new SemanticError("Variable already defined");
        }

        $frame->set($name);
    }

    public function resolve(Argument $arg): Symbol
    {
        $type = $arg->getType();

        if ($type === ArgType::VAR) {
            // The argument is a variable -- find its value in memory
            $frame = $this->frame($arg->getFrame());

            if ($frame == null) {
                throw new FrameAccessError();
            }

            return $frame->get($arg->getName());
        }

        if ($type === ArgType::LABEL || $type === ArgType::TYPE) {
            // The argument is a label or type -- return its value
            return $arg->getValue();
        }

        // The argument is a constant -- return the symbol object
        return $arg->getConstantSymbol();
    }

    public function set(string $name, string $frameType, DataType $type, mixed $value): void
    {
        $frame = $this->frame($frameType);

        if ($frame == null) {
            // The frame is undefined
            throw new FrameAccessError();
        }
        if ($frame->get($name) == null) {
            // The item was not found
            throw new VariableAccessError("Variable " . $name . " does not exist in " . $frameType);
        }

        $frame->set($name, $type, $value);
    }

    public function read(DataType $type): mixed
    {
        switch ($type) {
            case DataType::INT:
                return $this->reader->readInt();
            case DataType::BOOL:
                return $this->reader->readBool();
            case DataType::STRING:
                return $this->reader->readString();
        }
    }

    public function write(Symbol $symb): void
    {
        $type = $symb->getType();

        if ($type === DataType::NIL) {
            $this->writer->writeString("nil@nil");
        } else {
            $this->writer->writeString($symb->getValue());
        }
    }
}
