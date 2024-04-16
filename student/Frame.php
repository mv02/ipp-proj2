<?php

namespace IPP\Student;

class Frame
{
    /** @var array<string, Variable> */
    private array $values = [];

    /**
     * Get variable with given name.
     *
     * @param string $name Name of the variable.
     *
     * @return Variable Variable saved in the frame.
     */
    public function get(string $name): Variable
    {
        return $this->values[$name];
    }

    /**
     * Set value and type of variable with given name.
     * If the variable does not exist, an empty one with given name is created.
     *
     * @param string $name Name of the variable.
     * @param DataType $type Data type the variable should be assigned.
     * @param int|bool|string|null $value Value the variable should be assigned.
     */
    public function set(string $name, DataType $type = DataType::NONE, mixed $value = null): void
    {
        if (!array_key_exists($name, $this->values)) {
            $this->values[$name] = new Variable();
        }

        $this->values[$name]->setType($type);
        $this->values[$name]->setValue($value);
    }

    /**
     * Check whether a variable with given name exists.
     *
     * @return bool true if the variable exists, false otherwise.
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->values);
    }
}
