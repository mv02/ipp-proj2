<?php

namespace IPP\Student\Instruction;

use IPP\Core\Exception\NotImplementedException;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class TypeInstruction extends Instruction
{
    public function execute(Environment $env): void
    {
        throw new NotImplementedException;
    }
}