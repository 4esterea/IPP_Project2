<?php

namespace IPP\Student;

use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\StackException;
use IPP\Student\Types\AbstractType;

class Stack
{
    /**
     * @var StackElement[]
     */
    private array $dataStack;

    /**
     * @var StackElement[]
     */
    private array $callStack;

    public function __construct()
    {
        $this->dataStack = [];
        $this->callStack = [];
    }

    public function pushs(AbstractType $element): void
    {
        array_push($this->dataStack, new StackElement($element->getValue(), $element->getType()));
    }
    public function pops(): ?StackElement
    {
        // check if stack is empty
        if(empty($this->dataStack))
            throw new StackException("Data stack is empty");
        return array_pop($this->dataStack);
    }

    public function pushc(int $pointer): void
    {
        array_push($this->callStack, new StackElement($pointer, 'return'));
    }
    public function popc(): ?StackElement
    {
        // check if stack is empty
        if(empty($this->callStack))
            throw new StackException("Call stack is empty");
        return array_pop($this->callStack);
    }

    // method for printing stack for debugging purposes
    public function printStack(OutputWriter $stream): void
    {
        $stream->writeString("\n[Data stack]:\n\n");
        if(empty($this->dataStack))
            $stream->writeString("Empty\n");
        foreach ($this->dataStack as $element) {
            $stream->writeString($element->getType() . ": " . $element->getValue() . "\n");
        }
        $stream->writeString("\n[Call stack]:\n\n");
        if(empty($this->callStack))
            $stream->writeString("Empty\n");
        foreach ($this->callStack as $element) {
            $stream->writeString($element->getType() . ": " . $element->getValue() . "\n");
        }
    }
}

// StackElement class for storing elements in stack and data transfer between instructions and frames
class StackElement extends AbstractType
{
    public function __construct(mixed $value, string $type)
    {
        $this->type = $type;
        $this->value = $value;
    }
}