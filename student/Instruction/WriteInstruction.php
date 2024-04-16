<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Exception\ValueError;
use IPP\Student\Instruction;

class WriteInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::SYMB];

    public function execute(Environment $env): void
    {
        $arg = $this->args[0];

        $symbol = $env->resolve($arg);

        if ($symbol->getType() === DataType::NONE) {
            throw new ValueError();
        }

        $env->write($symbol);
    }
}
