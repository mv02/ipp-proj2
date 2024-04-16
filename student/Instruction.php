<?php

namespace IPP\Student;

use IPP\Student\Exception\InvalidSourceStructure;
use IPP\Student\Exception\SemanticError;

abstract class Instruction
{
    /** @var int */
    private int $order;
    /** @var string */
    private string $opcode;
    /** @var Argument[] */
    protected array $args = [];
    /** @var ArgType[] */
    protected array $expectedArgs = [];

    /**
     * Execute the instruction.
     *
     * @param Environment $env Current runtime environment.
     */
    abstract public function execute(Environment $env): void;

    /**
     * @param \DOMNode $node The DOM node to parse instruction from.
     */
    public function __construct(\DOMNode $node)
    {
        $this->order = $node->attributes["order"]->nodeValue;
        $this->opcode = strtoupper($node->attributes["opcode"]->nodeValue);
        $this->parseArgs($node);
        $this->validateArgs();
    }

    /**
     * Parse instruction arguments from children of given node.
     *
     * @param \DOMNode $node The DOM node to parse arguments from.
     *
     * @throws InvalidSourceStructure if argument elements are not valid.
     */
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

    /**
     * Validate count and types of parsed arguments.
     *
     * @throws InvalidSourceStructure if unexpected count or types of arguments were given.
     */
    private function validateArgs(): void
    {
        if (count($this->args) != count($this->expectedArgs)) {
            // Different argument count than expected
            throw new InvalidSourceStructure("Bad argument count, expected " . count($this->expectedArgs) . ", got " . count($this->args));
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

    /**
     * Get instruction order.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * Get instruction opcode.
     *
     * @return string
     */
    public function getOpcode(): string
    {
        return $this->opcode;
    }
}
