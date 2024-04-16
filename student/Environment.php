<?php

namespace IPP\Student;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exception\FrameAccessError;
use IPP\Student\Exception\SemanticError;
use IPP\Student\Exception\VariableAccessError;

class Environment
{
    /** @var int The instruction pointer. */
    private int $ip = 0;
    /** @var bool Whether the last executed instruction caused a jump. */
    private bool $jumped = false;
    /** @var array<string, int> A dict of labels and positions they refer to. */
    private array $labels = [];
    /** @var Frame The global frame. */
    private Frame $gf;
    /** @var Frame|null The temporary frame. */
    private ?Frame $tf = null;
    /** @var Stack<Frame> */
    private Stack $frameStack;
    /** @var Stack<int> */
    private Stack $callStack;
    /** @var Stack<Symbol> */
    private Stack $dataStack;

    /**
     * @param OutputWriter $writer Writer to use for writing output.
     * @param InputReader $reader Reader to use for reading input.
     */
    public function __construct(private OutputWriter $writer, private InputReader $reader)
    {
        $this->gf = new Frame();
        $this->frameStack = new Stack();
        $this->callStack = new Stack();
        $this->dataStack = new Stack();
    }

    /**
     * Return a frame of this environment corresponding to given abbreviation.
     *
     * @param string $type GF, TF or LF.
     *
     * @throws FrameAccessError if the frame is undefined.
     *
     * @return Frame The global, temporary or local frame.
     */
    private function getFrame(string $type): Frame
    {
        $frame = null;

        switch ($type) {
            case "GF":
                $frame = $this->gf;
                break;
            case "TF":
                $frame = $this->tf;
                break;
            case "LF":
                if (!$this->frameStack->isEmpty()) {
                    $frame = $this->frameStack->top();
                }
                break;
        }

        if ($frame === null) {
            throw new FrameAccessError($type . " is undefined");
        }

        return $frame;
    }

    /**
     * Create a new temporary frame.
     */
    public function createFrame(): void
    {
        $this->tf = new Frame();
    }

    /**
     * Push the current temporary frame onto the frame stack.
     *
     * @throws FrameAccessError if the temporary frame is undefined.
     */
    public function pushFrame(): void
    {
        if ($this->tf == null) {
            throw new FrameAccessError("Temporary frame is undefined");
        }

        $this->frameStack->push($this->tf);
        $this->tf = null;
    }

    /**
     * Move frame on top of the frame stack to temporary frame.
     *
     * @throws FrameAccessError if the frame stack is empty.
     */
    public function popFrame(): void
    {
        if ($this->frameStack->isEmpty()) {
            throw new FrameAccessError("Frame stack is empty");
        }

        $this->tf = $this->frameStack->pop();
    }

    /**
     * Define a new variable with given name in specific frame.
     *
     * @param string $name Name of the variable.
     * @param string $frameType GF, TF or LF.
     *
     * @throws FrameAccessError if the frame is undefined.
     * @throws SemanticError if a variable with given name already exists.
     */
    public function define(string $name, string $frameType): void
    {
        $frame = $this->getFrame($frameType);

        if ($frame->has($name)) {
            // The variable already exists
            throw new SemanticError(null, "Variable " . $name . " already exists in " . $frameType);
        }

        $frame->set($name);
    }

    /**
     * Resolve a value of an argument in context of this environment.
     *
     * @param Argument $arg The argument to resolve.
     *
     * @throws FrameAccessError if the argument is a variable and the frame is undefined.
     * @throws VariableAccessError if the argument is a variable and it does not exist.
     *
     * @return Symbol The resulting symbol -- variable or constant.
     */
    public function resolve(Argument $arg): Symbol
    {
        if ($arg->getType() === ArgType::VAR) {
            // The argument is a variable -- find its value in memory
            $name = $arg->getName();
            $frameType = $arg->getFrame();

            $frame = $this->getFrame($frameType);
            if (!$frame->has($name)) {
                // The variable does not exist
                throw new VariableAccessError("Variable " . $name . " does not exist in " . $frameType);
            }

            return $frame->get($name);
        }

        // The argument is a constant, label or type -- return the symbol object
        return $arg->getConstantSymbol();
    }

    /**
     * Set value and type of variable with given name in specific frame.
     *
     * @param string $name Name of the variable.
     * @param string $frameType GF, TF or LF.
     * @param DataType $type Data type the variable should be assigned.
     * @param int|bool|string|null $value Value the variable should be assigned.
     *
     * @throws FrameAccessError if the frame is undefined.
     * @throws VariableAccessError if the variable does not exist.
     */
    public function set(string $name, string $frameType, DataType $type, mixed $value): void
    {
        $frame = $this->getFrame($frameType);

        if (!$frame->has($name)) {
            // The variable does not exist
            throw new VariableAccessError("Variable " . $name . " does not exist in " . $frameType);
        }

        $frame->set($name, $type, $value);
    }

    /**
     * Read data of given type from standard input.
     *
     * @param DataType $type Data type to read.
     *
     * @return int|bool|string|null The value received from standard input.
     */
    public function read(DataType $type): mixed
    {
        switch ($type) {
            case DataType::INT:
                return $this->reader->readInt();
            case DataType::BOOL:
                return $this->reader->readBool();
            case DataType::STRING:
                return $this->reader->readString();
            default:
                return null;
        }
    }

    /**
     * Write value of given symbol to standard output.
     *
     * @param Symbol $symb The symbol to output.
     */
    public function write(Symbol $symb): void
    {
        $type = $symb->getType();

        if ($type === DataType::BOOL) {
            $this->writer->writeString($symb->getValue() ? "true" : "false");
        } elseif ($type === DataType::NIL) {
            $this->writer->writeString("");
        } else {
            $str = (string) $symb->getValue();

            // Escape sequences
            $str = preg_replace_callback("/\\\\[0-9]{3}/", function($matches) {
                $ord = (int) substr($matches[0], 1, 3);
                return mb_chr($ord);
            }, $str);

            $this->writer->writeString((string) $str);
        }
    }

    /**
     * Define a new label with given name.
     *
     * @param string $label Name of the label.
     * @param int $position Position the label refers to.
     *
     * @throws SemanticError if a label with given name already exists.
     */
    public function defineLabel(string $label, int $position): void
    {
        if (array_key_exists($label, $this->labels)) {
            throw new SemanticError(null, "Label already exists");
        }

        $this->labels[$label] = $position;
    }

    /**
     * Get value of the instruction pointer.
     *
     * @return int
     */
    public function getIp(): int
    {
        return $this->ip;
    }

    /**
     * Set value of the instruction pointer.
     *
     * @param int $ip
     */
    public function setIp(int $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * Jump to label with given name.
     *
     * @param string $label Name of the label to jump to.
     *
     * @throws SemanticError if the label does not exist.
     */
    public function jumpTo(string $label): void
    {
        if (!array_key_exists($label, $this->labels)) {
            throw new SemanticError(null, "Label does not exist");
        }

        $this->ip = $this->labels[$label];
        $this->jumped = true;
    }

    /**
     * Jump to a specific position by updating the instruction pointer.
     *
     * @param int $position The position to jump to.
     */
    public function jumpToPosition(int $position): void
    {
        $this->ip = $position;
        $this->jumped = true;
    }

    /**
     * Increment the instruction pointer by one,
     * unless a jump just updated it.
     */
    public function incrementIp(): void
    {
        if (!$this->jumped) {
            $this->ip++;
        }
        $this->jumped = false;
    }

    /**
     * Get the call stack.
     *
     * @return Stack<int>
     */
    public function getCallStack(): Stack
    {
        return $this->callStack;
    }

    /**
     * Get the data stack.
     *
     * @return Stack<Symbol>
     */
    public function getDataStack(): Stack
    {
        return $this->dataStack;
    }
}
