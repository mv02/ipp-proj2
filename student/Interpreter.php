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
            if ($node->nodeName == "#text") {
                continue;
            }

            if ($node->nodeName != "instruction") {
                throw new InvalidSourceStructure();
            }

            // Instantiate instruction object for each instruction node
            array_push($this->instructions, InstructionFactory::create($node));
        }

        // Sort instructions by order
        usort($this->instructions, fn($a, $b) => $a->getOrder() > $b->getOrder());

        // Execute instruction using the instruction pointer
        while ($this->ip < count($this->instructions)) {
            $this->instructions[$this->ip]->execute($this->env, $this->stdout);
            $this->ip++;
        }

        return 0;
    }
}
