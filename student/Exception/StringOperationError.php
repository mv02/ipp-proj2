<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use IPP\Student\Instruction;
use Throwable;

class StringOperationError extends IPPException
{
    public function __construct(?Instruction $instruction = null, string $message = "Invalid string operation", ?Throwable $previous = null)
    {
        if ($instruction != null) {
            $message = "Instruction " . $instruction->getOrder() . " (" . $instruction->getOpcode() . "): " . $message;
        }

        parent::__construct($message, ReturnCode::STRING_OPERATION_ERROR, $previous, false);
    }
}
