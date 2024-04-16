<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Instruction;

class JumpIfNeqInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::LABEL, ArgType::SYMB, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $label = $env->resolve($this->args[0]);
        $a = $env->resolve($this->args[1]);
        $b = $env->resolve($this->args[2]);

        $aType = $a->getType();
        $bType = $b->getType();

        if ($aType !== $bType && $aType !== DataType::NIL && $bType !== DataType::NIL) {
            throw new OperandTypeError($this);
        }

        if ($a->getValue() !== $b->getValue()) {
            $env->jumpTo((string) $label->getValue());
        }
    }
}
