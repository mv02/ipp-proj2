<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\StringOperationError;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Instruction;

class Int2CharInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $ord = $env->resolve($this->args[1]);

        $ordValue = $ord->getValue();

        if ($ord->getType() !== DataType::INT) {
            throw new OperandTypeError($this);
        }

        $char = mb_chr($ordValue);

        if ($char === false) {
            throw new StringOperationError($this, "Invalid ordinal value");
        }

        $env->set($destArg->getName(), $destArg->getFrame(), DataType::STRING, $char);
    }
}
