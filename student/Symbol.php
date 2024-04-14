<?php

namespace IPP\Student;

class Symbol
{
    protected DataType $type;
    protected mixed $value;

    public function __construct(DataType $type = DataType::NIL, mixed $value = null)
    {
        $this->type = $type;

        switch ($type) {
            case DataType::INT:
                $this->value = (int) $value;
                break;
            case DataType::BOOL:
                $this->value = (bool) $value;
                break;
            case DataType::STRING:
                $this->value = (string) $value;
                break;
            default:
                $this->value = $value;
        }
    }

    public function getType(): DataType
    {
        return $this->type;
    }

    public function getValue(): mixed
    {
        switch ($this->type) {
            case DataType::INT:
                return (int) $this->value;
            case DataType::BOOL:
                return (bool) $this->value;
            case DataType::STRING:
                return (string) $this->value;
            default:
                return $this->value;
        }
    }
}
