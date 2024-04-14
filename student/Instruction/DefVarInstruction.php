<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Instruction;

class DefVarInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $arg = $this->args[0];
        $env->define($arg->getName(), $arg->getFrame());
    }
}
