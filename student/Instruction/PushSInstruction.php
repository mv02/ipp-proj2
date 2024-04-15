<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class PushSInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $symbol = $env->resolve($this->args[0]);

        $env->getDataStack()->push($symbol);
    }
}
