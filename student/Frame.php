<?php

namespace IPP\Student;

class Frame
{
    private array $values = [];

    public function get(string $key): ?Variable
    {
        return $this->values[$key] ?? null;
    }

    public function set(string $key, VarType $type = VarType::NIL, mixed $value = null): void
    {
        if (!array_key_exists($key, $this->values)) {
            $this->values[$key] = new Variable();
        }

        $this->values[$key]->setType($type);
        $this->values[$key]->setValue($value);
    }
}
