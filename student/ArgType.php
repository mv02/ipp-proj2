<?php

namespace IPP\Student;

enum ArgType: string
{
    case LABEL = "label";
    case TYPE = "type";
    case VAR = "var";
    case CONST = "const";
    case SYMB = "symb";
}
