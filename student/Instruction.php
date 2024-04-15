<?php

namespace IPP\Student;

use IPP\Student\Exception\InvalidSourceStructure;
use IPP\Student\Exception\SemanticError;

abstract class Instruction
{
    private int $order;
    private string $opcode;
    protected array $args = [];
    protected array $expectedArgs = [];

    abstract public function execute(Environment $env): void;

    public function __construct(\DOMNode $node)
    {
        $this->order = $node->attributes["order"]->nodeValue;
        $this->opcode = $node->attributes["opcode"]->nodeValue;
        $this->parseArgs($node);
        $this->validateArgs();
    }

    private function parseArgs(\DOMNode $node): void
    {
        foreach ($node->childNodes as $attr) {
            $name = $attr->nodeName;
            $value = $attr->nodeValue;

            if ($name === "#text") {
                // Skip text nodes
                continue;
            }

            if (in_array($name, ["arg1", "arg2", "arg3"])) {
                $n = (int) $name[3] - 1;

                if (array_key_exists($n, $this->args)) {
                    // Argument with this number already exists
                    throw new InvalidSourceStructure("Duplicit argument");
                }
                if ($attr->attributes["type"] === null) {
                    // The element has no "type" attribute
                    throw new InvalidSourceStructure("Missing attribute type");
                }

                // Create an argument instance
                $this->args[$n] = new Argument($value, $attr->attributes["type"]->nodeValue);
                continue;
            }

            // Not an arg element
            throw new InvalidSourceStructure("Unexpected element");
        }

        if (count($this->args) > 0) {
            $minN = min(array_keys($this->args));
            $maxN = max(array_keys($this->args));

            if ($minN !== 0 || $maxN !== count($this->args) - 1) {
                // Arguments are not numbered correctly
                throw new InvalidSourceStructure("Missing argument");
            }
        }
    }

    private function validateArgs(): void
    {
        if (count($this->args) != count($this->expectedArgs)) {
            // Different argument count than expected
            throw new SemanticError($this, "Bad argument count, expected " . count($this->expectedArgs) . ", got " . count($this->args));
        }

        foreach ($this->args as $i => $arg) {
            $expected = $this->expectedArgs[$i];
            $type = $arg->getType();

            if ($expected === $type) {
                // Same type as expected
                continue;
            }
            if ($expected === ArgType::SYMB && ($type === ArgType::CONST || $type === ArgType::VAR)) {
                // Expected symbol, i.e. constant or variable
                continue;
            }

            // No match found
            throw new InvalidSourceStructure("Invalid argument, expected " . $expected->name);
        }
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getOpcode(): string
    {
        return $this->opcode;
    }
}
