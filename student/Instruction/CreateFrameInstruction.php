<?php

namespace IPP\Student\Instruction;

use IPP\Student\Environment;
use IPP\Student\Instruction;

class CreateFrameInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        $env->createFrame();
    }
}
