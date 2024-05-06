<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;

class iJUMPIFEQ extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check(['label', ['int', 'bool', 'string', 'nil', 'type'], ['int', 'bool', 'string', 'nil', 'type']], 2);
        $label = $this->getOperands()[0];
        $operand1 = $this->getOperands()[1];
        $operand2 = $this->getOperands()[2];
        $label->checkExistence($flow);
        if ($operand1->getValue() == $operand2->getValue()) {
            $flow->setInstructionPointer($flow->getLabels()[$label->getName()]->getPointer());
        }
        return 0;
    }
}