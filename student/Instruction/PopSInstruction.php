<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Exception\ValueError;
use IPP\Student\Instruction;

class PopSInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $dataStack = $env->getDataStack();

        if ($dataStack->isEmpty()) {
            throw new ValueError("Data stack is empty");
        }

        $symbol = $dataStack->pop();
        $env->set($destArg->getName(), $destArg->getFrame(), $symbol->getType(), $symbol->getValue());
    }
}
