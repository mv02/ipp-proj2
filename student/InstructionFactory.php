<?php

namespace IPP\Student;

use IPP\Core\Exception\NotImplementedException;
use IPP\Student\Instruction;

class InstructionFactory
{
    public static function create(\DOMNode $node): Instruction
    {
        $opcode = $node->attributes["opcode"]->nodeValue;

        switch ($opcode) {
            case "MOVE":
                return new Instruction\MoveInstruction($node);
            case "CREATEFRAME":
                return new Instruction\CreateFrameInstruction($node);
            case "PUSHFRAME":
                return new Instruction\PushFrameInstruction($node);
            case "POPFRAME":
                return new Instruction\PopFrameInstruction($node);
            case "DEFVAR":
                return new Instruction\DefVarInstruction($node);
            case "CALL":
                return new Instruction\CallInstruction($node);
            case "RETURN":
                return new Instruction\ReturnInstruction($node);
            case "PUSHS":
                return new Instruction\PushSInstruction($node);
            case "POPS":
                return new Instruction\PopSInstruction($node);
            case "ADD":
                return new Instruction\AddInstruction($node);
            case "SUB":
                return new Instruction\SubInstruction($node);
            case "MUL":
                return new Instruction\MulInstruction($node);
            case "IDIV":
                return new Instruction\IdivInstruction($node);
            case "LT":
                return new Instruction\LtInstruction($node);
            case "GT":
                return new Instruction\GtInstruction($node);
            case "EQ":
                return new Instruction\EqInstruction($node);
            case "AND":
                return new Instruction\AndInstruction($node);
            case "OR":
                return new Instruction\OrInstruction($node);
            case "NOT":
                return new Instruction\NotInstruction($node);
            case "INT2CHAR":
                return new Instruction\Int2CharInstruction($node);
            case "STRI2INT":
                return new Instruction\Stri2IntInstruction($node);
            case "READ":
                return new Instruction\ReadInstruction($node);
            case "WRITE":
                return new Instruction\WriteInstruction($node);
            case "CONCAT":
                return new Instruction\ConcatInstruction($node);
            case "STRLEN":
                return new Instruction\StrLenInstruction($node);
            case "GETCHAR":
                return new Instruction\GetCharInstruction($node);
            case "SETCHAR":
                return new Instruction\SetCharInstruction($node);
            case "TYPE":
                return new Instruction\TypeInstruction($node);
            case "LABEL":
                return new Instruction\LabelInstruction($node);
            case "JUMP":
                return new Instruction\JumpInstruction($node);
            case "JUMPIFEQ":
                return new Instruction\JumpIfEqInstruction($node);
            case "JUMPIFNEQ":
                return new Instruction\JumpIfNeqInstruction($node);
            case "EXIT":
                return new Instruction\ExitInstruction($node);
            case "DPRINT":
                return new Instruction\DPrintInstruction($node);
            case "BREAK":
                return new Instruction\BreakInstruction($node);
            default:
                throw new NotImplementedException("Unsupported opcode");
        }
    }
}
