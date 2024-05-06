<?php

namespace IPP\Student\Instructions;
use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\OperandTypeException;
use IPP\Student\Exceptions\SourceException;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\Types\Label;
use IPP\Student\Types\Variable;

abstract class AbstractInstruction
{
    /**
     * @var array<mixed>
     */
    protected array $operands;
    /**
     * @param array<mixed> $operands
     */
    public function __construct(array $operands)
    {
        $this->operands = $operands;
    }
    /**
     * @param array<mixed> $requiredOperands
     */

    // check if the operands are correct
    public function check(array $requiredOperands, int $mode = 0): void
    {
        $this->checkOperandCount($requiredOperands);

        // mode 1: check if the types of operands are the same
        if ($mode == 1){
            if ($this->operands[1]->getType() != $this->operands[2]->getType()) {
                throw new OperandTypeException("Operand types do not match");
            }
        }

        // mode 2: check if the types of operands are the same, letting nil and type pass
        if ($mode == 2){
            if ($this->operands[1]->getType() != $this->operands[2]->getType() && !in_array($this->operands[1]->getType(), ['nil', 'type']) && !in_array($this->operands[2]->getType(), ['nil', 'type'])) {
                throw new OperandTypeException("Operand types do not match");
            }
        }
        $this->checkOperandTypes($requiredOperands);
    }
    /**
     * @param array<mixed> $requiredOperands
     */

    // check if the number of operands is correct
    private function checkOperandCount(array $requiredOperands): void
    {
        if (count($this->operands) !== count($requiredOperands)) {
            throw new SourceException("Invalid number of operands");
        }
    }
    /**
     * @param array<mixed> $requiredOperands
     */

    // check if the types of operands are correct
    private function checkOperandTypes(array $requiredOperands): void
    {
        for($i = 0; $i < count($this->operands); $i++) {
            if ($requiredOperands[$i] == 'var') {
                if (!($this->operands[$i] instanceof Variable)) {
                    throw new OperandTypeException("Invalid operand type ");
                }
            } elseif ($requiredOperands[$i] == 'type') {
                if (!in_array($this->operands[$i]->getValue(), ['int', 'bool', 'string', 'nil'])) {
                    throw new OperandTypeException("Invalid operand type: " . $this->operands[$i]->getType());
                }
            } elseif ($requiredOperands[$i] == 'label') {
                if (!($this->operands[$i] instanceof Label)) {
                    throw new OperandTypeException("Invalid operand type: " . $this->operands[$i]->getType());
                }
            } elseif (is_array($requiredOperands[$i])) {
                if (!in_array($this->operands[$i]->getType(), $requiredOperands[$i])) {
                    throw new OperandTypeException("Invalid operand type: " . $this->operands[$i]->getType());
                }
            }
        }
    }

    /**
     * @return array<mixed>
     */
    public function getOperands(): array
    {
        return $this->operands;
    }

    // method to update variable operands from given frame
    protected function updateVar(Frames $frames) : void
    {
        foreach ($this->getOperands() as $operand) {
            if ($operand instanceof Variable) {
                $operand->update($frames);
            }
        }
    }

    // method to correctly print the output of the instruction
    protected function printCorrect(OutputWriter $stream): void
    {
        $content = $this->getOperands()[0];
        $value = $content->getValue();
        if ($value === 'nil') return;
        if($content->getType() == 'bool'){
            $stream->writeString($value ? 'true' : 'false');
        } else {
            $stream->writeString((string)$content->getValue());
        }
    }
    abstract public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int;
}