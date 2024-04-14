<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class MoveInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $srcArg = $this->args[1];
        $destArg = $this->args[0];

        $src = $env->resolve($srcArg);
        $dest = $env->resolve($destArg);

        $env->set($destArg->getName(), $destArg->getFrame(), $src->getType(), $src->getValue());
    }
}
