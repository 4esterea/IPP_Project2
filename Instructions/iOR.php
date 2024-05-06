<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iOR extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['bool'], ['bool']]);
        $operand1 = $this->getOperands()[1];
        $operand2 = $this->getOperands()[2];
        $result = new StackElement($operand1->getValue() || $operand2->getValue(), 'bool');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}