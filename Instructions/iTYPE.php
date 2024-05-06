<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;
use IPP\Student\StackElement;

class iTYPE extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['var', ['int', 'bool', 'string', 'nil']]);
        $operand = $this->getOperands()[1];
        $result = new StackElement($operand->getType(), 'type');
        $frames->setFrameValue($this->getOperands()[0], $result);
        return 0;
    }
}