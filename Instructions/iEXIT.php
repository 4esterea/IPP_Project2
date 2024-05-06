<?php

namespace IPP\Student\Instructions;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Exceptions\OperandValueException;
use IPP\Student\Frames;
use IPP\Student\ProgramFlow;
use IPP\Student\Stack;

class iEXIT extends AbstractInstruction
{
    public function execute(Frames $frames, Stack $stack, ProgramFlow $flow, OutputWriter $out, OutputWriter $err, InputReader $in): int
    {
        $this->updateVar($frames);
        $this->check([['int']]);
        $exitCode = $this->getOperands()[0]->getValue();
        if ($exitCode < 0 || $exitCode > 9) {
            throw new OperandValueException("Invalid exit code");
        }
        return $exitCode;
    }
}