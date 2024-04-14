<?php

namespace IPP\Student;

class Variable extends Symbol
{
    public function setType(DataType $type): void
    {
        $this->type = $type;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
