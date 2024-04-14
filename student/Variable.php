<?php

namespace IPP\Student;

class Variable
{
    private mixed $value = null;
    private ?VarType $type = null;

    public function getValue(): mixed
    {
        switch ($this->type) {
            case VarType::INT:
                return (int) $this->value;
            case VarType::BOOL:
                return (bool) $this->value;
            case VarType::STRING:
                return (string) $this->value;
            default:
                return $this->value;
        }
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getType(): VarType
    {
        return $this->type;
    }

    public function setType(VarType $type): void
    {
        $this->type = $type;
    }
}
