<?php

namespace IPP\Student;

class Variable extends Symbol
{
    /**
     * Set variable data type.
     *
     * @param DataType $type
     */
    public function setType(DataType $type): void
    {
        $this->type = $type;
    }

    /**
     * Set variable value.
     *
     * @param int|bool|string|null $value
     */
    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
