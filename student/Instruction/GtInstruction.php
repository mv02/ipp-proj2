<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\OperandTypeError;
use IPP\Student\Instruction;

class GtInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $a = $env->resolve($this->args[1]);
        $b = $env->resolve($this->args[2]);

        $aType = $a->getType();
        $bType = $b->getType();

        if ($aType !== $bType || $aType === DataType::NIL || $bType === DataType::NIL) {
            throw new OperandTypeError($this);
        }

        $env->set($destArg->getName(), $destArg->getFrame(), DataType::BOOL, $a->getValue() > $b->getValue());
    }
}
