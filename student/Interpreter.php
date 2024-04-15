<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Student\Exception\InvalidSourceStructure;

class Interpreter extends AbstractInterpreter
{
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

            array_push($this->instructions, $instruction);
        }

        $instructionCount = count($this->instructions);

        // Sort instructions by order
        usort($this->instructions, fn($a, $b) => $a->getOrder() > $b->getOrder());

        // Execute instructions using the instruction pointer
        while (($ip = $this->env->getIp()) < $instructionCount) {
            $this->instructions[$ip]->execute($this->env, $this->stdout);
            $this->env->incrementIp();
        }

        return 0;
    }
}
