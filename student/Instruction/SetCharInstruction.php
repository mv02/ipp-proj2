<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\StringOperationError;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Instruction;

class SetCharInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $str = $env->resolve($this->args[0]);
        $index = $env->resolve($this->args[1]);
        $replace = $env->resolve($this->args[2]);

        $strValue = (string) $str->getValue();
        $indexValue = (int) $index->getValue();
        $replaceValue = (string) $replace->getValue();

        if ($index->getType() !== DataType::INT || $replace->getType() !== DataType::STRING) {
            throw new OperandTypeError($this);
        }
        if ($indexValue < 0 || $indexValue >= strlen($strValue)) {
            throw new StringOperationError($this, "String index out of range");
        }

        $strValue[$indexValue] = $replaceValue;
        $env->set($destArg->getName(), $destArg->getFrame(), DataType::STRING, $strValue);
    }
}
