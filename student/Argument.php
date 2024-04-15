<?php

namespace IPP\Student;

use IPP\Core\Exception\NotImplementedException;
use ValueError;

class Argument
{
    private string $value;
    private ArgType $type;
    private string $frame;
    private string $name;
    private Symbol $constant;

    function __construct(string $value, string $type)
    {
        $this->value = $value;

        $constType = DataType::tryFrom($type);

        if ($constType !== null) {
            // The argument is a constant
            $this->type = ArgType::CONST;
            $this->constant = new Symbol($constType, $value);
        } else {
            // The argument is a label, type or variable
            try {
                $this->type = ArgType::from($type);
            } catch (ValueError $e) {
                throw new NotImplementedException("Unsupported argument type: " . $type);
            }
            $this->constant = new Symbol(DataType::STRING, $value);
        }

        if ($this->type == ArgType::VAR) {
            $split = explode("@", $this->value);
            $this->frame = $split[0];
            $this->name = $split[1];
        }
    }

    public function getConstantSymbol(): Symbol
    {
        return $this->constant;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): ArgType
    {
        return $this->type;
    }

    public function getFrame(): string
    {
        return $this->frame;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
