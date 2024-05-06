<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iSTRI2INT extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['string'], ['int']]);
        $operand1 = $this->getOperands()[1];
        $operand2 = $this->getOperands()[2];
        if ($operand2->getValue() < 0 || $operand2->getValue() >= strlen($operand1->getValue())) {
            throw new StringException("Invalid string index");
        }
        $result = new StackElement(mb_ord($operand1->getValue()[$operand2->getValue()]), 'int');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}