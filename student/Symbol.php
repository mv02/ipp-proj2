<?php

namespace IPP\Student;

class Symbol
{
    /** @var DataType */
    protected DataType $type;
    /** @var int|bool|string|null */
    protected mixed $value;

    /**
     * @param DataType $type
     * @param int|bool|string|null $value
     */
    public function __construct(DataType $type = DataType::NONE, mixed $value = null)
    {
        $this->type = $type;

        switch ($type) {
            case DataType::INT:
                $this->value = (int) $value;
                break;
            case DataType::BOOL:
                $this->value = $value == "true";
                break;
            case DataType::STRING:
                $this->value = (string) $value;
                break;
            default:
                $this->value = null;
        }
    }

    /**
     * Get symbol data type.
     *
     * @return DataType
     */
    public function getType(): DataType
    {
        return $this->type;
    }

    /**
     * Get symbol data type in string representation.
     *
     * @return string
     */
    public function getTypeString(): string
    {
        switch ($this->type) {
            case DataType::INT:
                return "int";
            case DataType::BOOL:
                return "bool";
            case DataType::STRING:
                return "string";
            case DataType::NIL:
                return "nil";
            default:
                return "";
        }
    }

    /**
     * Get symbol value.
     *
     * @return int|bool|string|null
     */
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
