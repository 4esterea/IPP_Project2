<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;

class iJUMP extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->check(['label']);
        $label = $this->getOperands()[0];
        $label->checkExistence($flow);
        $flow->setInstructionPointer($flow->getLabels()[$label->getName()]->getPointer());
        return 0;
    }
}