<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Exception\OperandValueError;
use IPP\Student\Instruction;

class ExitInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $code = $env->resolve($this->args[0]);

        $codeValue = $code->getValue();

        if ($code->getType() !== DataType::INT) {
            throw new OperandTypeError($this);
        }
        if ($codeValue < 0 || $codeValue > 9) {
            throw new OperandValueError($this);
        }

        exit($codeValue);
    }
}
