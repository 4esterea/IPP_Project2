<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iINT2CHAR extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['int']]);
        $operand = $this->getOperands()[1];
        if ($operand->getValue() < 0 || $operand->getValue() > 255) {
            throw new StringException("Invalid ordinal value");
        }
        $result = new StackElement(mb_chr($operand->getValue(), 'UTF-8'), 'string');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}