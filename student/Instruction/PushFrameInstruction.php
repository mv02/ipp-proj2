<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Instruction;

class PushFrameInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $env->pushFrame();
    }
}
