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

    public function resolve(Argument $arg): string
    {
        if ($arg->getType() == ArgType::VAR) {
            $name = $arg->getName();
            $frame = $this->frame($arg->getFrame());

            if ($frame == null) {
                throw new FrameAccessError();
            }

            return $frame->get($name)->getValue();
        }

        return $arg->getValue();
    }

    public function set(string $name, string $frameType, VarType $type, mixed $value): void
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

    public function read(VarType $type): mixed
    {
        switch ($type) {
            case VarType::INT:
                return $this->reader->readInt();
            case VarType::BOOL:
                return $this->reader->readBool();
            case VarType::STRING:
                return $this->reader->readString();
        }
    }

    public function writeString(string $value): void
    {
        $this->writer->writeString($value);
    }
}
