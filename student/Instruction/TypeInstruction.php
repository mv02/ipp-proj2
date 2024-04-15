<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class TypeInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $symbol = $env->resolve($this->args[1]);

        $env->set($destArg->getName(), $destArg->getFrame(), DataType::STRING, $symbol->getTypeString());
    }
}
