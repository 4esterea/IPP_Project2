<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\OperandValueException;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iIDIV extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['int'], ['int']]);
        $operand1 = $this->getOperands()[1];
        $operand2 = $this->getOperands()[2];
        if ($operand2->getValue() == 0) {
            throw new OperandValueException("Division by zero");
        }
        $quotient = $operand1->getValue() / $operand2->getValue();
        $result = new StackElement($quotient, 'int');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}