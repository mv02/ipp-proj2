<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Instruction;
use IPP\Student\VarType;

class MoveInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $src = $this->args[1];
        $dest = $this->args[0];

        $type = VarType::from($src->getType()->value);
        $value = $env->resolve($src);

        $env->set($dest->getName(), $dest->getFrame(), $type, $value);
    }
}
