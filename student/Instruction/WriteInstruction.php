<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Instruction;

class WriteInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $arg = $this->args[0];

        $value = $env->resolve($arg);

        $env->writeString($value);
    }
}
