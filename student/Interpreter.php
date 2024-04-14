<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Student\Exception\InvalidSourceStructure;

class Interpreter extends AbstractInterpreter
{
    private int $ip = 0;                // Instruction pointer
    private array $instructions = [];   // List of instructions
    private Environment $env;

    public function execute(): int
    {
        $this->env = new Environment($this->stdout, $this->input);

        $dom = $this->source->getDOMDocument();

        foreach ($dom->getRootNode()->firstChild->childNodes as $node) {
            $name = $node->nodeName;

            if ($name == "#text") {
                // Skip text nodes
                continue;
            }

            if ($name != "instruction") {
                // Not an instruction element
                throw new InvalidSourceStructure("Unexpected element");
            }
            if ($node->attributes["order"] == null) {
                // The element has no "order" attribute
                throw new InvalidSourceStructure("Missing instruction order");
            }
            if ($node->attributes["opcode"] == null) {
                // The element has no "opcode" attribute
                throw new InvalidSourceStructure("Missing instruction opcode");
            }

            // Instantiate instruction object for each instruction node
            $instruction = InstructionFactory::create($node);
            $order = $instruction->getOrder();

            if ($order <= 0) {
                // Instruction order is not a natural number
                throw new InvalidSourceStructure("Invalid instruction order");
            }
            if (array_key_exists($order, $this->instructions)) {
                // Instruction with this order already exists
                throw new InvalidSourceStructure("Duplicit instruction order");
            }

            $this->instructions[$order] = $instruction;
        }

        $instructionKeys = array_keys($this->instructions);

        // Execute instruction using the instruction pointer
        while ($this->ip < count($this->instructions)) {
            $key = $instructionKeys[$this->ip];
            $this->instructions[$key]->execute($this->env, $this->stdout);
            $this->ip++;
        }

        return 0;
    }
}
