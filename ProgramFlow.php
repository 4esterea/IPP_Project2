<?php

namespace IPP\Student;

use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\LabelException;
use IPP\Student\Types\Label;

class ProgramFlow
{
    /**
     * @var Label[]
     */
    private array $labels;
    private int $instructionPointer;

    public function __construct()
    {
        $this->instructionPointer = 0;
        $this->labels = [];
    }

    public function setInstructionPointer(int $instructionPointer): void
    {
        $this->instructionPointer = $instructionPointer;
    }

    public function getInstructionPointer(): int
    {
        return $this->instructionPointer;
    }

    public function incrementInstructionPointer(): void
    {
        $this->instructionPointer++;
    }
    /**
     * @return Label[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    public function setLabel(Label $label): void
    {
        // check if label already exists
        if (empty($this->labels[$label->getName()])){
            $this->labels[$label->getName()] = $label;
        } else {
            throw new LabelException("Label already exists");
        }
    }

    // method for printing program flow for debugging purposes
    public function printProgramFlow(OutputWriter $stream): void
    {
        $stream->writeString("\n[Labels]:\n\n");
        if (empty($this->labels)) {
            $stream->writeString("Empty\n");
        }
        foreach ($this->labels as $label) {
            $stream->writeString($label->getName() . ": " . $label->getPointer() . "\n");
        }
        $stream->writeString("\n[AbstractInstruction pointer]: " . $this->getInstructionPointer() . "\n\n");
    }
}