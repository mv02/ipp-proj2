<?php

namespace IPP\Student\Exception;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;
use IPP\Student\Instruction;
use Throwable;

class SemanticError extends IPPException
{
    public function __construct(?Instruction $instruction = null, string $message = "Semantic error", ?Throwable $previous = null)
    {
        if ($instruction != null) {
            $message = "Instruction " . $instruction->getOrder() . " (" . $instruction->getOpcode() . "): " . $message;
        }

        parent::__construct($message, ReturnCode::SEMANTIC_ERROR, $previous, false);
    }
}
