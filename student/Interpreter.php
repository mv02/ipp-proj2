<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Student\Exception\InvalidSourceStructure;

class Interpreter extends AbstractInterpreter
{
    /** @var Instruction[] List of instructions. */
    private array $instructions = [];
    /** @var Environment Current runtime environment. */
    private Environment $env;

    public function execute(): int
    {
        $this->env = new Environment($this->stdout, $this->input);

        $dom = $this->source->getDOMDocument();
        $program = $dom->getRootNode()->firstChild;

        if ($program->nodeName !== "program") {
            // Not a program element
            throw new InvalidSourceStructure("Unexpected element");
        }
        if ($program->attributes["language"] === null) {
            // The element has no "language" attribute
            throw new InvalidSourceStructure("Missing language attribute");
        }
        if ($program->attributes["language"]->nodeValue !== "IPPcode24") {
            // The "language" attribute is invalid
            throw new InvalidSourceStructure("Invalid language");
        }

        foreach ($program->childNodes as $node) {
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
        usort($this->instructions, fn($a, $b) => $a->getOrder() - $b->getOrder());

        // Execute LABEL instructions
        while (($ip = $this->env->getIp()) < $instructionCount) {
            $instruction = $this->instructions[$ip];
            if ($instruction->getOpcode() === "LABEL") {
                $instruction->execute($this->env);
            }
            $this->env->incrementIp();
        }

        $this->env->setIp(0);

        // Execute instructions using the instruction pointer
        while (($ip = $this->env->getIp()) < $instructionCount) {
            $this->instructions[$ip]->execute($this->env);
            $this->env->incrementIp();
        }

        return 0;
    }
}
