<?php

namespace IPP\Student;

enum DataType: string
{
    case INT = "int";
    case BOOL = "bool";
    case STRING = "string";
    case NIL = "nil";
    case NONE = "";
}
