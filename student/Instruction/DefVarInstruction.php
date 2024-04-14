<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class DefVarInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR];

    public function execute(Environment $env): void
    {
        $arg = $this->args[0];
        $env->define($arg->getName(), $arg->getFrame());
    }
}
