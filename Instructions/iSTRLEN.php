<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iSTRLEN extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['string']]);
        $operand = $this->getOperands()[1];
        $result = new StackElement(strlen($operand->getValue()), 'int');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}