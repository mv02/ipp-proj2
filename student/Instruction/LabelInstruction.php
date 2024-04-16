<?php

namespace IPP\Student\Instruction;

use IPP\Student\ArgType;
use IPP\Student\Environment;
use IPP\Student\Instruction;

class LabelInstruction extends Instruction
{
    protected array $expectedArgs = [ArgType::LABEL];
    private bool $executed = false;

    public function execute(Environment $env): void
    {
        if ($this->executed) {
            return;
        }

        $this->executed = true;

        $label = $env->resolve($this->args[0]);

        $env->defineLabel((string) $label->getValue(), $env->getIp() + 1);
    }
}
