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

    function __construct(string $value, string $type)
    {
        $this->value = $value;

        try {
            $this->type = ArgType::from($type);
        } catch (ValueError $e) {
            throw new NotImplementedException("Unsupported argument type: " . $type);
        }

        if ($this->type == ArgType::VAR) {
            $split = explode("@", $this->value);
            $this->frame = $split[0];
            $this->name = $split[1];
        }
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
