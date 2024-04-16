<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;
use IPP\Student\Symbol;

class PushSInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $src = $env->resolve($this->args[0]);
        $symbol = new Symbol($src->getType(), $src->getValue());

        $env->getDataStack()->push($symbol);
    }
}
