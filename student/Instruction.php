<?php

namespace IPP\Student;

abstract class Instruction
{
    private int $order;
    private string $opcode;
    protected array $args = [];

    abstract public function execute(Environment $env): void;

    public function __construct(\DOMNode $node)
    {
        $this->order = $node->attributes["order"]->nodeValue;
        $this->opcode = $node->attributes["opcode"]->nodeValue;

        foreach ($node->childNodes as $attr) {
            if ($attr->nodeName == "#text") {
                continue;
            }

            if (!str_starts_with($attr->nodeName, "arg")) {
                continue;
            }

            $value = $attr->nodeValue;
            $type = $attr->attributes[0]->nodeValue;

            array_push($this->args, new Argument($value, $type));
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
