<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Exception\ValueError;
use IPP\Student\Instruction;

class ReturnInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $callStack = $env->getCallStack();

        if ($callStack->isEmpty()) {
            throw new ValueError("Call stack is empty");
        }

        $position = $callStack->pop();
        $env->jumpToPosition($position);
    }
}
