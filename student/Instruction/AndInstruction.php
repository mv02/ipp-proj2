<?php

namespace IPP\Student\Instruction;

use IPP\Core\Exception\NotImplementedException;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class AndInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        throw new NotImplementedException;
    }
}
