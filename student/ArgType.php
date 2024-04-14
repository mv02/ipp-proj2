<?php

namespace IPP\Student;

enum ArgType: string {
    case INT = "int";
    case BOOL = "bool";
    case STRING = "string";
    case NIL = "nil";
    case LABEL = "label";
    case TYPE = "type";
    case VAR = "var";
}
