<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use IPP\Student\Instruction;
use Throwable;

class OperandValueError extends IPPException
{
    public function __construct(?Instruction $instruction = null, string $message = "Invalid operand value", ?Throwable $previous = null)
    {
        if ($instruction != null) {
            $message = "Instruction " . $instruction->getOrder() . " (" . $instruction->getOpcode() . "): " . $message;
        }

        parent::__construct($message, ReturnCode::OPERAND_VALUE_ERROR, $previous, false);
    }
}
