<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Exception\StringOperationError;
use IPP\Student\Instruction;

class GetCharInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $str = $env->resolve($this->args[1]);
        $index = $env->resolve($this->args[2]);

        $strValue = $str->getValue();
        $indexValue = $index->getValue();

        if ($str->getType() !== DataType::STRING || $index->getType() !== DataType::INT) {
            throw new OperandTypeError($this);
        }
        if ($indexValue < 0 || $indexValue >= strlen($strValue)) {
            throw new StringOperationError($this, "String index out of range");
        }

        $env->set($destArg->getName(), $destArg->getFrame(), DataType::STRING, $strValue[$indexValue]);
    }
}
