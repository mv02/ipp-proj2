<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\DataType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class ReadInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::VAR, ArgType::TYPE];

    public function execute(Environment $env): void
    {
        $destArg = $this->args[0];

        $type = DataType::from($this->args[1]->getValue());
        $value = $env->read($type);

        if ($value === null) {
            $type = DataType::NIL;
        }

        $env->set($destArg->getName(), $destArg->getFrame(), $type, $value);
    }
}
