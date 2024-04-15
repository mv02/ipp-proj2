<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class JumpInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::LABEL];

    public function execute(Environment $env): void
    {
        $label = $env->resolve($this->args[0]);

        $env->jumpTo($label->getValue());
    }
}
