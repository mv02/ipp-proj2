<?php

namespace IPP\Student;

use IPP\Student\Exception\InvalidSourceStructure;
use ValueError;

class Argument
{
    /** @var string Raw value of the argument. */
    private string $value;
    /** @var ArgType Type of the argument. */
    private ArgType $type;
    /** @var string Frame type if the argument is a variable. */
    private string $frame;
    /** @var string Name if the argument is a variable. */
    private string $name;
    /** @var Symbol Symbol representing a non-variable. */
    private Symbol $symbol;

    /**
     * @param string $value Inner text of the DOM node.
     * @param string $type Value of "type" attribute.
     */
    function __construct(string $value, string $type)
    {
        $this->value = trim($value);

        $constType = DataType::tryFrom($type);

        if ($constType !== null) {
            // The argument is a constant
            $this->type = ArgType::CONST;
            $this->symbol = new Symbol($constType, $this->value);
        } else {
            // The argument is a label, type or variable
            try {
                $this->type = ArgType::from($type);
            } catch (ValueError $e) {
                throw new InvalidSourceStructure("Unknown argument type: " . $type);
            }
            $this->symbol = new Symbol(DataType::STRING, $this->value);
        }

        if ($this->type == ArgType::VAR) {
            $split = explode("@", $this->value);
            $this->frame = $split[0];
            $this->name = $split[1];

            if (!in_array($this->frame, ["GF", "LF", "TF"])) {
                throw new InvalidSourceStructure("Invalid frame: " . $this->frame);
            }
        }
    }

    /**
     * Get symbol representing a non-variable.
     *
     * @return Symbol
     */
    public function getSymbol(): Symbol
    {
        return $this->symbol;
    }

    /**
     * Get raw value of the argument.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the argument type.
     *
     * @return ArgType
     */
    public function getType(): ArgType
    {
        return $this->type;
    }

    /**
     * Get frame type if the argument is a variable.
     *
     * @return string
     */
    public function getFrame(): string
    {
        return $this->frame;
    }

    /**
     * Get name if the argument is a variable.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
