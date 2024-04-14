<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Instruction;

class StrLenInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $a = $env->resolve($this->args[1]);

        if ($a->getType() !== DataType::STRING) {
            throw new OperandTypeError($this);
        }

        $env->set($destArg->getName(), $destArg->getFrame(), DataType::INT, strlen($a->getValue()));
    }
}
