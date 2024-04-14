<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Instruction;

class PopFrameInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $env->popFrame();
    }
}
