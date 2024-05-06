<?php

namespace IPP\Student\Instructions;
use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;

class iBREAK extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->check([]);
        $frames->printFrames($err);
        $stack->printStack($err);
        $flow->printProgramFlow($err);
        return 0;
    }
}

