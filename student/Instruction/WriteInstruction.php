<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class WriteInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $arg = $this->args[0];

        $symbol = $env->resolve($arg);

        $env->write($symbol);
    }
}
